<?php

namespace Pterodactyl\Http\Controllers\Api\Client;

use Illuminate\Http\Request;
use Pterodactyl\Http\Controllers\Controller;

class GetUserRoleController extends Controller
{
    public function index(Request $request)
    {
        if($request->user()->role) { $role = true; } else { $role = false; }

        return [
            'success' => true,
            'data' => [
                'role' => $role,
            ],
        ];
    }
}
