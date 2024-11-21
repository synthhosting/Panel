<?php

namespace Pterodactyl\Http\Controllers\Admin\Helionix;

use Illuminate\View\View;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Helionix\AuthenticationSettingsRequest;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class AuthenticationController extends Controller
{
    /**
     * BaseController constructor.
     */
    public function __construct(private AlertsMessageBag $alert, private SettingsRepositoryInterface $helionix, private ViewFactory $view)
    {
    }

    /**
     * Return the admin index view.
     */
    public function index(): View
    {
        return $this->view->make('admin.helionix.authentication', [
            'auth_title' => $this->helionix->get('helionix::helionix:authentication:title', 'Helionix'),
            'auth_description' => $this->helionix->get('helionix::helionix:authentication:description', 'Easily customize your Pterodactyl panel with the Helionix Theme'),
            'auth_layout' => $this->helionix->get('helionix::helionix:authentication:layout', 1),
            'auth_register_status' => $this->helionix->get('helionix::helionix:authentication:register:status', true),
            //google auth
            'auth_google_status' => $this->helionix->get('helionix::helionix:authentication:google:status', false),
            'auth_google_client_id' => $this->helionix->get('helionix::helionix:authentication:google:client_id', ''),
            'auth_google_client_secret' => $this->helionix->get('helionix::helionix:authentication:google:client_secret', ''),
            'auth_google_redirect' => $this->helionix->get('helionix::helionix:authentication:google:redirect', ''),
            // discord auth
            'auth_discord_status' => $this->helionix->get('helionix::helionix:authentication:discord:status', false),
            'auth_discord_client_id' => $this->helionix->get('helionix::helionix:authentication:discord:client_id', ''),
            'auth_discord_client_secret' => $this->helionix->get('helionix::helionix:authentication:discord:client_secret', ''),
            'auth_discord_redirect' => $this->helionix->get('helionix::helionix:authentication:discord:redirect', ''),
            // github auth
            'auth_github_status' => $this->helionix->get('helionix::helionix:authentication:github:status', false),
            'auth_github_client_id' => $this->helionix->get('helionix::helionix:authentication:github:client_id', ''),
            'auth_github_client_secret' => $this->helionix->get('helionix::helionix:authentication:github:client_secret', ''),
            'auth_github_redirect' => $this->helionix->get('helionix::helionix:authentication:github:redirect', ''),
        ]);
    }

    public function store(AuthenticationSettingsRequest $request)
    {
        foreach ($request->normalize() as $key => $value) {
            $this->helionix->set('helionix::' . $key, $value);
        }

        $this->alert->success('Theme settings have been updated successfully.')->flash();
        return redirect()->route('admin.helionix.authentication');
    }
}