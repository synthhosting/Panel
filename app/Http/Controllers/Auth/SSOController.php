<?php

namespace Pterodactyl\Http\Controllers\Auth;

use Pterodactyl\Models\User;
use Pterodactyl\Facades\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Pterodactyl\Http\Controllers\Controller;

class SSOController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        $user = Socialite::driver($provider)->user();

        $user = User::where('email', $user->email)->first();

        if (!$user) return redirect('/');

        Activity::event('auth:success')->withRequestMetadata()->subject($user)->log();

        Auth::login($user, $remember = true);

        return redirect('/');
    }
}