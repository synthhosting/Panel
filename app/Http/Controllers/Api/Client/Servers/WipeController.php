<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Servers;

use DateTimeZone;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Pterodactyl\Models\Wipe;
use Illuminate\Http\Response;
use Pterodactyl\Models\Server;
use Pterodactyl\Models\WipeMap;
use Illuminate\Http\JsonResponse;
use Pterodactyl\Models\WipeCommand;
use Pterodactyl\Jobs\WipeServerJob;
use Pterodactyl\Transformers\Api\Client\WipeTransformer;
use Pterodactyl\Repositories\Wings\DaemonPowerRepository;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;
use Pterodactyl\Http\Requests\Api\Client\Servers\Wipe\WipeRequest;
use Pterodactyl\Http\Requests\Api\Client\Servers\Wipe\AddMapRequest;
use Pterodactyl\Http\Requests\Api\Client\Servers\Wipe\TimezoneRequest;
use Pterodactyl\Http\Requests\Api\Client\Servers\Wipe\DeleteWipeRequest;

class WipeController extends ClientApiController
{
    /**
     * ServersController constructor.
     */
    public function __construct(protected DaemonPowerRepository $powerRepository) {
        parent::__construct();
    }

    public function index(Server $server): array
    {
        return [
            'name' => $server->variables->where('env_variable', 'HOSTNAME')->first()->server_value,
            'description' => $server->variables->where('env_variable', 'DESCRIPTION')->first()->server_value,
            'timezones' => DateTimeZone::listIdentifiers(DateTimeZone::ALL),
            'maps' => $server->wipemaps,
            'wipes' => $this->fractal->collection(Wipe::where('server_id', $server->id)->where('time', '>', new Carbon(Carbon::now(), new DateTimeZone($server->timezone ?? DateTimeZone::listIdentifiers(DateTimeZone::ALL)[0])))->get())
                        ->transformWith($this->getTransformer(WipeTransformer::class))
                        ->toArray(),
        ];
    }

    public function store(Server $server, WipeRequest $request, $wipe = null): JsonResponse
    {
        if (!$wipe) {
            $wipe = array_merge(['uuid' => Uuid::uuid4()->toString(), 'server_id' => $server->id], $request->all());

            if ($request->input('schedule')) {
                $wipe = Wipe::create($wipe);
                foreach($request->input('commands') as $key => $value) {
                    if ($value) {
                        WipeCommand::create([
                            'wipe_id' => $wipe->id,
                            'command' => $value,
                            'time' => $request->input('command_times')[$key],
                        ]);
                    }
                }
            } else {
                $this->powerRepository->setServer($server)->send('stop');
                dispatch(new WipeServerJob($server, $request->all()))->delay(Carbon::now()->addMinute());
            }
        } else {
            $wipe = Wipe::where('id', $wipe)->first();

            foreach($wipe->commands as $command) {
                $command->delete();
            }

            foreach($request->input('commands') as $key => $value) {
                if ($value) {
                    WipeCommand::create([
                        'wipe_id' => $wipe->id,
                        'command' => $value,
                        'time' => $request->input('command_times')[$key],
                    ]);
                }
            }

            $wipe->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'size' => $request->input('size'),
                'seed' => $request->input('seed'),
                'random_seed' => $request->input('random_seed'),
                'random_level' => $request->input('random_level'),
                'level' => $request->input('level'),
                'files' => $request->input('files'),
                'blueprints' => $request->input('blueprints'),
                'time' => $request->input('time'),
                'repeat' => $request->input('repeat'),
            ]);
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    public function timezone(Server $server, TimezoneRequest $request): JsonResponse
    {
        $server->update([
            'timezone' => $request->input('timezone'),
        ]);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteWipeRequest $request, Server $server, Wipe $wipe): JsonResponse
    {
        foreach($wipe->commands as $command) {
            $command->delete();
        }

        $wipe->delete();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    public function map(AddMapRequest $request, Server $server): JsonResponse
    {
        WipeMap::create([
            'server_id' => $server->id,
            'name' => $request->input('name'),
            'map' => $request->input('map'),
        ]);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    public function deleteMap(DeleteWipeRequest $request, Server $server, WipeMap $wipemap)
    {
        $wipemap->delete();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}