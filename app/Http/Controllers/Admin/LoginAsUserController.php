<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Models\User;

class LoginAsUserController extends Controller
{
    public function loginasuser($id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('index')->with('error', 'User not found.');
        }

        Auth::logout();
        $request->session()->flush();
        Auth::login($user, true);

        return redirect()->route('index');
    }
}