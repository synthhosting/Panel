<?php

namespace Pterodactyl\Http\Controllers\Admin\Helionix;

use Illuminate\View\View;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Helionix\ServerSettingsRequest;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class ServerController extends Controller
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
        return $this->view->make('admin.helionix.server', [
            'layout_console' => $this->helionix->get('helionix::helionix:layout_console', 1),
            'bar_cpu' => $this->helionix->get('helionix::helionix:bar_cpu', true),
            'bar_memory' => $this->helionix->get('helionix::helionix:bar_memory', true),
            'bar_disk' => $this->helionix->get('helionix::helionix:bar_disk', true),
        ]);
    }

    public function store(ServerSettingsRequest $request)
    {
        foreach ($request->normalize() as $key => $value) {
            $this->helionix->set('helionix::' . $key, $value);
        }

        $this->alert->success('Theme settings have been updated successfully.')->flash();
        return redirect()->route('admin.helionix.server');
    }
}