<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Servers;

use Pterodactyl\Models\Server;
use Illuminate\Support\Facades\DB;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;

class DowntimeController extends ClientApiController
{
    /**
     * DowntimeController constructor.
     */
    public function __construct()
    {
    }

    /**
     * Returns possible downtime for this server.
     *
     * @throws \Pterodactyl\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function index(Server $server): array
    {
        $node = DB::table('nodes')->where('id', '=', $server->node_id)->first();

        if (strtotime($node->downtime_end) < strtotime('now')) {
            DB::table('nodes')
            ->where('id', '=', $node->id)
            ->update([
                'has_downtime' => false,
            ]);

            $data = [
                'has_downtime' => false,
                'start' => null,
                'end' => null,
            ];

            return $data;
        } else {
            $start = date_create($node->downtime_start);
            $start = date_format($start, 'm-d-Y H:i');

            $end = date_create($node->downtime_end);
            $end = date_format($end, 'm-d-Y H:i');

            $data = [
                'has_downtime' => $node->has_downtime,
                'start' => $start,
                'end' => $end,
            ];

            return $data;
        }
    }
}
