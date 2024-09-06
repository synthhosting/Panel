<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Pterodactyl\Models\User;
use Pterodactyl\Models\Role;
use Pterodactyl\Models\Subuser;
use Pterodactyl\Models\Permission;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\Http\RedirectResponse;
use Pterodactyl\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * PermissionController constructor.
     */
    public function __construct(private AlertsMessageBag $alert)
    {
    }

    public function index()
    {
        $roles = Role::get();

        return view('admin.permissions.index', [
            'roles' => $roles,
        ]);
    }

    public function new()
    {
        return view('admin.permissions.new', [
            'permissions' => Permission::permissions(),
        ]);
    }

    public function create(Request $request)
    {
        if(empty($request->input('permissions') ?? [])) {
            $this->alert->danger('Select at least 1 client permission.')->flash();
            return redirect()->back();
        }

        Role::insert([
            'name' => $request->name,
            'color' => $request->color,
            'permissions' => json_encode($this->getDefaultPermissions($request)),
            'p_settings' => $request->settings,
            'p_api' => $request->api,
            'p_permissions' => $request->permission,
            'p_databases' => $request->databases,
            'p_locations' => $request->locations,
            'p_nodes' => $request->nodes,
            'p_servers' => $request->servers,
            'p_users' => $request->users,
            'p_mounts' => $request->mounts,
            'p_nests' => $request->nests,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->alert->success(trans('Role successfully created.'))->flash();

        return redirect()->route('admin.permissions.index');
    }

    public function edit(Role $role)
    {
        return view('admin.permissions.edit', [
            'role' => $role,
            'client_permissions' => Permission::permissions(),
        ]);
    }

    public function update(Role $role, Request $request)
    {
        if(empty($request->input('permissions') ?? [])) {
            $this->alert->danger('Select at least 1 client permission.')->flash();
            return redirect()->back();
        }

        $users = User::where('role', $role->id)->get();

        foreach($users as $user) {
            $subusers = Subuser::where('user_id', $user->id)->get();
            foreach($subusers as $subuser) {
                if($this->getDefaultPermissionsDB(json_decode($role->permissions)) == $subuser->permissions) {
                    Subuser::where('id', $subuser->id)->update([
                        'permissions' => $this->getDefaultPermissions($request),
                    ]);
                }
            }
        }

        $permissions = $this->getDefaultPermissions($request);

        $role->update([
            'name' => $request->name,
            'color' => $request->color,
            'permissions' => json_encode($permissions),
            'p_settings' => $request->settings,
            'p_api' => $request->api,
            'p_permissions' => $request->permission,
            'p_databases' => $request->databases,
            'p_locations' => $request->locations,
            'p_nodes' => $request->nodes,
            'p_servers' => $request->servers,
            'p_users' => $request->users,
            'p_mounts' => $request->mounts,
            'p_nests' => $request->nests,
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        $this->alert->success(trans('Role successfully updated.'))->flash();
        return redirect()->route('admin.permissions.index');
    }

    public function destroy(Role $role)
    {
        $users = User::all();
        foreach($users as $user) {
            if($user->role === $role->id) {
                $this->alert->danger(trans('This role is still in use by one or more users.'))->flash();
                return redirect()->route('admin.permissions.index');
            }

        }

        $role->delete();

        $this->alert->success(trans('Role successfully deleted.'))->flash();
        return redirect()->route('admin.permissions.index');
    }

    protected function getDefaultPermissions(Request $request): array
    {
        return array_unique(array_merge($request->input('permissions') ?? [], [Permission::ACTION_WEBSOCKET_CONNECT]));
    }
    protected function getDefaultPermissionsDB($permissions): array
    {
        return array_unique(array_merge($permissions ?? [], [Permission::ACTION_WEBSOCKET_CONNECT]));
    }
}