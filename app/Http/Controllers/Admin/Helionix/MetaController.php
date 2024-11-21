<?php

namespace Pterodactyl\Http\Controllers\Admin\Helionix;

use Illuminate\View\View;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Helionix\MetaSettingsRequest;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class MetaController extends Controller
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
        return $this->view->make('admin.helionix.meta', [
            'meta_logo' => $this->helionix->get('helionix::helionix:meta_logo', '/favicons/android-chrome-512x512.png'),
            'meta_title' => $this->helionix->get('helionix::helionix:meta_title', 'Helionix'),
            'meta_description' => $this->helionix->get('helionix::helionix:meta_description', 'Easily customize your Pterodactyl panel with the Helionix Theme'),
            'meta_color' => $this->helionix->get('helionix::helionix:meta_color', '#B3B3FF'),
        ]);
    }

    public function store(MetaSettingsRequest $request)
    {
        foreach ($request->normalize() as $key => $value) {
            $this->helionix->set('helionix::' . $key, $value);
        }

        $this->alert->success('Theme settings have been updated successfully.')->flash();
        return redirect()->route('admin.helionix.meta');
    }
}