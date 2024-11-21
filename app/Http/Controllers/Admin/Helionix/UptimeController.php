<?php

namespace Pterodactyl\Http\Controllers\Admin\Helionix;

use Illuminate\View\View;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Helionix\UptimeSettingsRequest;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class UptimeController extends Controller
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
        return $this->view->make('admin.helionix.uptime', [
            'uptime_nodes_status' => $this->helionix->get('helionix::helionix:uptime_nodes_status', true),
            'uptime_nodes_unit' => $this->helionix->get('helionix::helionix:uptime_nodes_unit', 'percent'),
        ]);
    }

    public function store(UptimeSettingsRequest $request)
    {
        foreach ($request->normalize() as $key => $value) {
            $this->helionix->set('helionix::' . $key, $value);
        }

        $this->alert->success('Theme settings have been updated successfully.')->flash();
        return redirect()->route('admin.helionix.uptime');
    }
}