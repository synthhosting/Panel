<?php

namespace Pterodactyl\Models;

class Role extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'permissions';

    /**
     * Fields that are mass assignable.
     */
    protected $fillable = [
        'name',
        'color',
        'p_settings',
        'p_api',
        'p_permissions',
        'p_databases',
        'p_locations',
        'p_nodes',
        'p_servers',
        'p_users',
        'p_mounts',
        'p_nests',
        'permissions',
    ];

    public static array $validationRules = [
        'name' => 'required|regex:/^([\w .-]{1,100})$/',
        'color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'p_settings' => 'required|numeric|min:0|max:3',
        'p_api' => 'required|numeric|min:0|max:3',
        'p_permissions' => 'required|numeric|min:0|max:3',
        'p_databases' => 'required|numeric|min:0|max:3',
        'p_locations' => 'required|numeric|min:0|max:3',
        'p_nodes' => 'required|numeric|min:0|max:3',
        'p_servers' => 'required|numeric|min:0|max:3',
        'p_users' => 'required|numeric|min:0|max:3',
        'p_mounts' => 'required|numeric|min:0|max:3',
        'p_nests' => 'required|numeric|min:0|max:3',
    ];
}
