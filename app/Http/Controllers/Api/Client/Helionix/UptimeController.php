<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Helionix;

use Illuminate\Http\Request;
use Pterodactyl\Models\Node;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;
use Pterodactyl\Transformers\Api\Application\NodeTransformer;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;

class UptimeController extends ClientApiController
{
    /**
     * UptimeController constructor.
     */
    public function __construct()
    {
        // 
    }

    /**
     * Return all the nodes currently available on the Panel.
     */
    public function index(): JsonResponse
    {
        $nodes = Node::where('uptime', true)->get();

        $transformedNodes = fractal($nodes, new NodeTransformer())->toArray();

        $filteredNodes = array_map(function ($node) {
            return [
                'name' => $node['attributes']['name'],
                'fqdn' => $node['attributes']['fqdn'],
                'port' => $node['attributes']['daemon_listen'],
                'memory' => $node['attributes']['memory'],
                'disk' => $node['attributes']['disk'],
                'allocated_resources' => $node['attributes']['allocated_resources'],
                'uptime_duration' => (int) Redis::get("helionix:{$node['attributes']['fqdn']}:{$node['attributes']['daemon_listen']}"),
            ];
        }, $transformedNodes['data']);

        return response()->json(['data' => $filteredNodes]);
    }
}
