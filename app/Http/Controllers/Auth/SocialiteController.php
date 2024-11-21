<?php

namespace Pterodactyl\Http\Controllers\Auth;

use Ramsey\Uuid\Uuid;
use Pterodactyl\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class SocialiteController
{
    protected $helionix;
    protected $allowedProviders = ['google', 'discord', 'github'];

    public function __construct(SettingsRepositoryInterface $helionix)
    {
        $this->helionix = $helionix;
    }

    public function redirectToProvider($provider)
    {
        if (!in_array($provider, $this->allowedProviders) || !$this->isProviderEnabled($provider)) {
            return redirect('/');
        }

        config([
            "services.$provider.client_id" => $this->helionix->get("helionix::helionix:authentication:$provider:client_id", ''),
            "services.$provider.client_secret" => $this->helionix->get("helionix::helionix:authentication:$provider:client_secret", ''),
            "services.$provider.redirect" => $this->helionix->get("helionix::helionix:authentication:$provider:redirect", ''),
        ]);

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        if (!in_array($provider, $this->allowedProviders) || !$this->isProviderEnabled($provider)) {
            return redirect('/');
        }

        config([
            "services.$provider.client_id" => $this->helionix->get("helionix::helionix:authentication:$provider:client_id", ''),
            "services.$provider.client_secret" => $this->helionix->get("helionix::helionix:authentication:$provider:client_secret", ''),
            "services.$provider.redirect" => $this->helionix->get("helionix::helionix:authentication:$provider:redirect", ''),
        ]);

        $socialUser = Socialite::driver($provider)->stateless()->user();

        return $this->handleSocialUser($socialUser, $provider);
    }

    private function isProviderEnabled($provider)
    {
        return $this->helionix->get("helionix::helionix:authentication:{$provider}:status", true);
    }

    private function handleSocialUser($socialUser, $provider)
    {
        $existingUser = User::where('email', '=', $socialUser->getEmail())->first();

        if ($existingUser) {
            if ($existingUser->provider !== $provider) {
                return redirect('/')->with('error', 'The email is already registered with another provider.');
            }
            Auth::loginUsingId($existingUser->id);
        } else {
            $this->CreateNewAccount($socialUser, $provider);
        }

        return redirect('/');
    }

    private function CreateNewAccount($socialUser, $provider)
    {
        $email = $socialUser->getEmail();
        $username = $socialUser->getNickname() ?: $socialUser->getName();
        $username = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace(' ', '', $username));

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->name_first = $username;
        $user->name_last = $username;
        $user->password = Hash::make($username . time() . $email . substr(md5(mt_rand()), 0, 7));
        $user->uuid = Uuid::uuid4()->toString();
        $user->provider = $provider;
        $user->save();
        Auth::loginUsingId($user->id);
    }
}