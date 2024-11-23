<?php

namespace Pterodactyl\Http\Controllers\Api\Remote\Backups;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Pterodactyl\Extensions\AktiCubeDevelopmentTeam\NodeBackup\NodeBackupManager;
use Pterodactyl\Models\Backup;
use Illuminate\Http\JsonResponse;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Exceptions\Http\HttpForbiddenException;
use Pterodactyl\Extensions\Backups\BackupManager;
use Pterodactyl\Extensions\Filesystem\S3Filesystem;
use Pterodactyl\Models\NodeBackupServer;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BackupRemoteUploadController extends Controller
{
    public const DEFAULT_MAX_PART_SIZE = 5 * 1024 * 1024 * 1024;

    public function __construct(
        private BackupManager $backupManager,
        private NodeBackupManager $nodeBackupManager
    )
    {
    }

    public function __invoke(Request $request, string $backup): JsonResponse
    {
        // Get the node associated with the request.
        /** @var \Pterodactyl\Models\Node $node */
        $node = $request->attributes->get('node');

        // Get the size query parameter.
        $size = (int) $request->query('size');
        if (empty($size)) {
            throw new BadRequestHttpException('A non-empty "size" query parameter must be provided.');
        }

        /** @var \Pterodactyl\Models\Backup $backupModel */
        $backupModel = Backup::query()
            ->where('uuid', $backup)
            ->first();

        if (is_null($backupModel)) {
            /** @var \Pterodactyl\Models\NodeBackupServer $nodeBackupServer */
            $nodeBackupServer = NodeBackupServer::query()
                ->where('uuid', $backup)
                ->firstOrFail();

            // Check that the backup is "owned" by the node making the request. This avoids other nodes
            // from messing with backups that they don't own.
            /** @var \Pterodactyl\Models\Server $server */
            $server = $nodeBackupServer->server();
            if ($server->node_id !== $node->id) {
                throw new HttpForbiddenException('You do not have permission to access that backup.');
            }

            if (!is_null($nodeBackupServer->completed_at)) {
                throw new ConflictHttpException('This backup is already in a completed state.');
            }

            $nodeBackupGroup = $nodeBackupServer->nodeBackupGroup();
            $s3Server = $nodeBackupGroup->s3Server();

            $adapter = $this->nodeBackupManager
                ->setNodeBackupGroup($nodeBackupGroup)
                ->adapter();

            if (!$adapter instanceof S3Filesystem) {
                throw new BadRequestHttpException('The configured backup adapter is not an S3 compatible adapter.');
            }

            $path = sprintf('%s/%s.tar.gz', $nodeBackupServer->server()->uuid, $nodeBackupServer->uuid);

            $client = $adapter->getClient();
            $expires = CarbonImmutable::now()->addMinutes($s3Server->presigned_url_lifespan ?? 60);

            $params = [
                'Bucket' => $adapter->getBucket(),
                'Key' => $path,
                'ContentType' => 'application/x-gzip',
            ];

            $storageClass = config('backups.disks.s3.storage_class');
            if (!is_null($storageClass)) {
                $params['StorageClass'] = $storageClass;
            }

            $result = $client->execute($client->getCommand('CreateMultipartUpload', $params));

            $params['UploadId'] = $result->get('UploadId');

            $maxPartSize = $s3Server->max_part_size ?? self::DEFAULT_MAX_PART_SIZE;

            $parts = [];
            for ($i = 0; $i < ($size / $maxPartSize); ++$i) {
                $parts[] = $client->createPresignedRequest(
                    $client->getCommand('UploadPart', array_merge($params, ['PartNumber' => $i + 1])),
                    $expires
                )->getUri()->__toString();
            }

            $nodeBackupServer->update(['upload_id' => $params['UploadId']]);

            return new JsonResponse([
                'parts' => $parts,
                'part_size' => $maxPartSize,
            ]);
        } else {
            // Check that the backup is "owned" by the node making the request. This avoids other nodes
            // from messing with backups that they don't own.
            /** @var \Pterodactyl\Models\Server $server */
            $server = $backupModel->server;
            if ($server->node_id !== $node->id) {
                throw new HttpForbiddenException('You do not have permission to access that backup.');
            }

            if (!is_null($backupModel->completed_at)) {
                throw new ConflictHttpException('This backup is already in a completed state.');
            }

            $adapter = $this->backupManager->adapter();
            if (!$adapter instanceof S3Filesystem) {
                throw new BadRequestHttpException('The configured backup adapter is not an S3 compatible adapter.');
            }

            $path = sprintf('%s/%s.tar.gz', $backupModel->server->uuid, $backupModel->uuid);

            $client = $adapter->getClient();
            $expires = CarbonImmutable::now()->addMinutes(config('backups.presigned_url_lifespan', 60));

            $params = [
                'Bucket' => $adapter->getBucket(),
                'Key' => $path,
                'ContentType' => 'application/x-gzip',
            ];

            $storageClass = config('backups.disks.s3.storage_class');
            if (!is_null($storageClass)) {
                $params['StorageClass'] = $storageClass;
            }

            $result = $client->execute($client->getCommand('CreateMultipartUpload', $params));

            $params['UploadId'] = $result->get('UploadId');

            $maxPartSize = $this->getConfiguredMaxPartSize();

            $parts = [];
            for ($i = 0; $i < ($size / $maxPartSize); ++$i) {
                $parts[] = $client->createPresignedRequest(
                    $client->getCommand('UploadPart', array_merge($params, ['PartNumber' => $i + 1])),
                    $expires
                )->getUri()->__toString();
            }

            $backupModel->update(['upload_id' => $params['UploadId']]);

            return new JsonResponse([
                'parts' => $parts,
                'part_size' => $maxPartSize,
            ]);
        }
    }

    private function getConfiguredMaxPartSize(): int
    {
        $maxPartSize = (int) config('backups.max_part_size', self::DEFAULT_MAX_PART_SIZE);
        if ($maxPartSize <= 0) {
            $maxPartSize = self::DEFAULT_MAX_PART_SIZE;
        }

        return $maxPartSize;
    }
}
