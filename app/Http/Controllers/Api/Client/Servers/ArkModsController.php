<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Servers;

use Pterodactyl\Models\Server;
use Illuminate\Support\Facades\Http;
use Pterodactyl\Exceptions\DisplayException;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;
use Pterodactyl\Http\Requests\Api\Client\Servers\Ark\GetModsRequest;

class ArkModsController extends ClientApiController
{
    public function index(Server $server, GetModsRequest $request): array
    {
        $response = Http::withHeaders([
            'x-api-key' => env('CURSEFORGE_API'),
        ])->get('https://api.curseforge.com/v1/mods/search', [
            'gameId' => 83374,
            'searchFilter' => $request->input('search'),
        ]);

        return $response->json();
    }

    public function modids(Server $server)
    {
        $variable = $server->variables->where('env_variable', 'MOD_IDS')->first();

        if (!$variable) throw new DisplayException('The MOD_IDS startup variable could not be found on this server, please contact an administrator.');

        return $variable->server_value;
    }

    public function installed(Server $server, GetModsRequest $request): array
    {
        $variable = $server->variables->where('env_variable', 'MOD_IDS')->first();

        if (!$variable) throw new DisplayException('The MOD_IDS startup variable could not be found on this server, please contact an administrator.');

        if($variable->server_value === '') return ['data' => []];

        $mods = explode(',', $variable->server_value);

        foreach($mods as $mod) {
            $mod = Http::withHeaders([
                'x-api-key' => env('CURSEFORGE_API'),
            ])->get('https://api.curseforge.com/v1/mods/' . $mod)->json()['data'];

            if(strpos(strtolower($mod['name']), strtolower($request->input('search'))) !== false) $response[] = $mod;
        }

        return ['data' => $response ?? []];
    }
}