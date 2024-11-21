<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Pterodactyl\Models\Role;
use Pterodactyl\Models\User;
use Pterodactyl\Models\Permission;
use Prologue\Alerts\AlertsMessageBag;
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
        return view('admin.permissions.index', [
            'roles' => Role::all(),
        ]);
    }

    public function new()
    {
        return view('admin.permissions.new');
    }

    public function create(Request $request)
    {
        Role::insert([
            'name' => $request->name,
            'color' => $request->color,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
            'p_settings' => $request->settings,
            'p_api' => $request->api,
            'p_permissions' => $request->permissions,
            'p_databases' => $request->databases,
            'p_locations' => $request->locations,
            'p_nodes' => $request->nodes,
            'p_servers' => $request->servers,
            'p_users' => $request->users,
            'p_mounts' => $request->mounts,
            'p_nests' => $request->nests,
        ]);

        $this->alert->success(
            trans('Role successfully created.')
        )->flash();

        return redirect()->route('admin.permissions.index');
    }

    public function edit(Role $role)
    {
        return view('admin.permissions.edit', [
            'role' => $role,
        ]);
    }

    public function update(Role $role, Request $request)
    {
        $role->update([
            'name' => $request->name,
            'color' => $request->color,
            'updated_at' => \Carbon\Carbon::now(),
            'p_settings' => $request->settings,
            'p_api' => $request->api,
            'p_permissions' => $request->permissions,
            'p_databases' => $request->databases,
            'p_locations' => $request->locations,
            'p_nodes' => $request->nodes,
            'p_servers' => $request->servers,
            'p_users' => $request->users,
            'p_mounts' => $request->mounts,
            'p_nests' => $request->nests,
        ]);

        $this->alert->success(trans('Role successfully updated.'))->flash();

        return redirect()->route('admin.permissions.index');
    }

    public function destroy(Role $role)
    {
        $users = User::all();
        foreach($users as $user) {
            if($user->role == $role->id) {
                $this->alert->danger(
                    trans('This role is still in use by one or more users.')
                )->flash();
                return redirect()->route('admin.permissions.index');
            }

        }

        $role->delete();

        $this->alert->success(
            trans('Role successfully deleted.')
        )->flash();

        return redirect()->route('admin.permissions.index');
    }
}
