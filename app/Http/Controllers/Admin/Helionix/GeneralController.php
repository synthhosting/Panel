<?php

namespace Pterodactyl\Http\Controllers\Admin\Helionix;

use Illuminate\View\View;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Helionix\GeneralSettingsRequest;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class GeneralController extends Controller
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
        return $this->view->make('admin.helionix.general', [
            'logo' => $this->helionix->get('helionix::helionix:logo', '/favicons/android-chrome-512x512.png'),
            'favicon' => $this->helionix->get('helionix::helionix:favicon', '/favicons/android-chrome-512x512.png'),
            'logo_only' => $this->helionix->get('helionix::helionix:logo_only', false),
            'logo_height' => $this->helionix->get('helionix::helionix:logo_height', '48px'),
        ]);
    }

    public function store(GeneralSettingsRequest $request)
    {
        foreach ($request->normalize() as $key => $value) {
            $this->helionix->set('helionix::' . $key, $value);
        }

        $this->alert->success('Theme settings have been updated successfully.')->flash();
        return redirect()->route('admin.helionix.general');
    }
}