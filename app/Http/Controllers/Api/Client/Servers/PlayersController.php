<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Servers;

use GameQ\GameQ;
use Pterodactyl\Models\Server;
use xPaw\SourceQuery\SourceQuery;
use Illuminate\Support\Facades\DB;
use Pterodactyl\Classes\MinecraftQuery;
use Pterodactyl\Exceptions\DisplayException;
use Pterodactyl\Classes\Exceptions\MinecraftQueryException;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;
use Pterodactyl\Http\Requests\Api\Client\Servers\GetServerRequest;

class PlayersController extends ClientApiController
{
    /**
     * @param GetServerRequest $request
     * @param Server $server
     * @return array
     * @throws DisplayException
     */
    public function index(GetServerRequest $request, Server $server)
    {
        $counters = DB::table('player_counter')->get();
        foreach ($counters as $counterItem) {
            if (in_array($server->egg_id, explode(',', $counterItem->egg_ids))) {
                $counter = $counterItem;
            }
        }

        $maxPlayers = 0;
        $onlinePlayers = 0;
        $players = [];

        if (isset($counter)) {
            $allocation = DB::table('allocations')->where('id', '=', $server->allocation_id)->get();
            $ip = $allocation[0]->ip;
            $port = $allocation[0]->port;

            if (in_array($counter->game, ['minecraft'])) {
                $query = null;

                try {
                    $query = new MinecraftQuery($ip, $port, 1);
                    $info = $query->Query();

                    if ($info === false) {
                        $query->Close();
                        $query->Connect();;
                        $query->QueryOldPre17();
                    }

                    $maxPlayers = $info['players']['max'] ?? 0;
                    $onlinePlayers = $info['players']['online'] ?? 0;

                    if (isset($info['players']['sample'])) {
                        foreach ($info['players']['sample'] as $player) {
                            $players[] = $player['name'];
                        }
                    }
                } catch (MinecraftQueryException $e) {
                    unset($counter);
                } finally {
                    if ($query !== null) {
                        $query->Close();
                    }
                }
            } else if (in_array($counter->game, ['minecraftpe'])) {
                $results = MinecraftQuery::minecraftPE($ip, $port);

                if ($results[0] == 1) {
                    $maxPlayers = isset($results[1]['players']) ? $results[1]['maxplayers'] : 0;
                    $onlinePlayers = $results[1]['numplayers'] ?? 0;
                }
            } else if (in_array($counter->game, ['csgo', 'arkse', 'unturned', 'cs16', 'killingfloor', 'hypercharge', 'eco'])) {
                $query = new SourceQuery();

                try {
                    $query->Connect($ip, $port, 1, SourceQuery::SOURCE);
                    $results = $query->GetInfo();

                    $maxPlayers = $results['MaxPlayers'] ?? 0;
                    $onlinePlayers = $results['Players'] ?? 0;
                } catch (\Exception $e) {
                    unset($counter);
                } finally {
                    $query->Disconnect();
                }
            } else {
                $GameQ = new GameQ();
                $options = [];

                if (in_array($counter->game, ['bf3', 'sevendaystodie', 'conanexiles', 'rust'])) {
                    $options['query_port'] = $port + 1;
                }

                $GameQ->addServer([
                    'type' => $counter->game,
                    'host' => sprintf('%s:%s', $ip, $port),
                    'options' => $options,
                ]);

                try {
                    $results = array_values($GameQ->process());

                    $maxPlayers = $results[0]['gq_maxplayers'] ?? 0;
                    $onlinePlayers = $results[0]['gq_numplayers'] ?? 0;

                    if ($counter->game == 'gta5m') {
                        $players = PlayersController::getFiveMPlayerList(sprintf('%s:%s', $ip, $port));
                    } else {
                        if (isset($results[$ip . ':' . $port]['players'])) {
                            foreach ($results[$ip . ':' . $port]['players'] as $player) {
                                $players[] = $player['gq_name'];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    unset($counter);

                    throw new DisplayException($e->getMessage());
                }
            }
        }

        if ($maxPlayers == 0) {
            unset($counter);
        }

        return [
            'success' => true,
            'data' => [
                'show' => isset($counter) ? 1 : 0,
                'maxPlayers' => $maxPlayers,
                'onlinePlayers' => $onlinePlayers,
                'players' => $players,
            ],
        ];
    }

    /**
     * @param $connection
     * @return array
     */
    private static function getFiveMPlayerList($connection)
    {
        $json = @file_get_contents(sprintf('http://%s/players.json', $connection));
        if ($json) {
            $players = [];
            $data = json_decode($json);

            foreach ($data as $player) {
                $players[] = $player->name;
            }

            return $players;
        }

        return [];
    }
}
