<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Servers;

use Exception;
use Pterodactyl\Models\Server;
use Pterodactyl\Facades\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Pterodactyl\Models\InstalledRustPlugin;
use Pterodactyl\Exceptions\DisplayException;
use Pterodactyl\Repositories\Wings\DaemonFileRepository;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;
use Pterodactyl\Http\Requests\Api\Client\Servers\Rustplugins\SearchRequest;
use Pterodactyl\Http\Requests\Api\Client\Servers\Rustplugins\InstallRequest;

class RustPluginsController extends ClientApiController
{
    /**
     * RustPluginsController constructor.
     */
    public function __construct(
        private DaemonFileRepository $fileRepository
    ) {
        parent::__construct();
    }

    public function index(SearchRequest $request, Server $server): array
    {
        try {
            $response = Http::get('https://umod.org/plugins/search.json?page=' . $request->input('page') . '&sort=latest_release_at&categories=rust&query=' . $request->input('search'));
        } catch(Exception $e) {
            throw new DisplayException('Something went wrong connecting with umod, please try again later.' . $e);
        }

        return $request->input('installed') ? ['data' => InstalledRustPlugin::where('server_id', $server->id)->where('title', 'LIKE', "{$request->input('search')}%")->get()->toArray()] : $response->json();
    }

    public function store(InstallRequest $request, Server $server): JsonResponse
    {
        $this->fileRepository->setServer($server)->pull(
            $request->input('url'),
            $request->input('directory'),
        );

        Activity::event('server:file.pull')
            ->property('directory', $request->input('directory'))
            ->property('url', $request->input('url'))
            ->log();

        if ($server->rustplugins->where('name', $request->input('data')['name'])->first()) {
            $server->rustplugins->where('name', $request->input('data')['name'])->first()->update($request->input('data'));
        } else {
            InstalledRustPlugin::create(array_merge($request->input('data'), ['server_id' => $server->id]));
        }

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
