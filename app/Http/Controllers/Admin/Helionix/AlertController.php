<?php

namespace Pterodactyl\Http\Controllers\Admin\Helionix;

use Illuminate\View\View;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Helionix\AlertSettingsRequest;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class AlertController extends Controller
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
        return $this->view->make('admin.helionix.alert', [
            'alert_type' => $this->helionix->get('helionix::helionix:alert_type', 'information'),
            'alert_clossable' => $this->helionix->get('helionix::helionix:alert_clossable', false),
            'alert_message' => $this->helionix->get('helionix::helionix:alert_message', 'Easily customize your Pterodactyl panel with the Helionix Theme'),
            'alert_color_information' => $this->helionix->get('helionix::helionix:alert_color_information', '#589AFC'),
            'alert_color_update' => $this->helionix->get('helionix::helionix:alert_color_update', '#45AF45'),
            'alert_color_warning' => $this->helionix->get('helionix::helionix:alert_color_warning', '#DF5438'),
            'alert_color_error' => $this->helionix->get('helionix::helionix:alert_color_error', '#D53F3F'),
        ]);
    }

    public function store(AlertSettingsRequest $request)
    {
        foreach ($request->normalize() as $key => $value) {
            $this->helionix->set('helionix::' . $key, $value);
        }

        $this->alert->success('Theme settings have been updated successfully.')->flash();
        return redirect()->route('admin.helionix.alert');
    }
}