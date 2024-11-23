<?php

namespace Pterodactyl\Services\AktiCubeDevelopmentTeam\NodeBackup\Servers;

use Illuminate\Support\Facades\Log;
use Pterodactyl\Exceptions\Http\Connection\DaemonConnectionException;
use Pterodactyl\Models\Server;
use Pterodactyl\Repositories\Wings\DaemonServerRepository;

class NodeBackupServerDeletionService
{
    public function __construct(
        private DaemonServerRepository $daemonServerRepository,
    ) {
    }

    public function handle(Server $server): void
    {
        try {
            $this->daemonServerRepository->setServer($server)->delete();
        } catch (DaemonConnectionException $exception) {
            Log::warning($exception);
        }
    }
}
