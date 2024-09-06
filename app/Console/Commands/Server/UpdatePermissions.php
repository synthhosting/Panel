<?php

namespace Pterodactyl\Console\Commands\Server;

use Pterodactyl\Models\User;
use Pterodactyl\Models\Role;
use Pterodactyl\Models\Server;
use Illuminate\Console\Command;
use Pterodactyl\Models\Subuser;
use Pterodactyl\Models\Permission;
use Pterodactyl\Services\Subusers\SubuserCreationService;

class UpdatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p:server:update-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates role permissions on newly created servers every minute.';

    /**
     * @var \Pterodactyl\Services\Subusers\SubuserCreationService
     */
    private $subuserCreationService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SubuserCreationService $subuserCreationService)
    {
        parent::__construct();
        $this->subuserCreationService = $subuserCreationService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach(Role::all() as $role) {
            $users = User::where('role', $role->id)->get();
            foreach($users as $user) {
                foreach(Server::whereNotIn('owner_id', [$user->id])->get() as $server) {
                    $subusers = Subuser::where('user_id', $user->id)->where('server_id', $server->id)->first();
                    if(empty($subusers)) {
                        $this->subuserCreationService->handle(
                            $server,
                            $user->email,
                            $this->getDefaultPermissions(json_decode($role->permissions))
                        );

                        Subuser::where('user_id', $user->id)->where('server_id', $server->id)->update([
                            'visible' => false,
                        ]);
                    }
                }
            }
        }

        return 0;
    }

    protected function getDefaultPermissions($permissions): array
    {
        return array_unique(array_merge($permissions ?? [], [Permission::ACTION_WEBSOCKET_CONNECT]));
    }
}
