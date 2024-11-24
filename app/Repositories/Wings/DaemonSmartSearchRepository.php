<?php

namespace Pterodactyl\Repositories\Wings;

use Webmozart\Assert\Assert;
use Pterodactyl\Models\Server;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use Pterodactyl\Exceptions\Http\Connection\DaemonConnectionException;

class DaemonSmartSearchRepository extends DaemonRepository
{
    /**
     * @param string $path
     * @param string $query
     * @return ResponseInterface
     * @throws DaemonConnectionException
     * @throws GuzzleException
     */
    public function smartSearch(string $path, string $query): ResponseInterface
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/files/search/smart', $this->server->uuid),
                [
                    'json' => [
                        'path' => $path,
                        'query' => $query,
                    ],
                    'timeout' => 300,
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
