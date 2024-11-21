<?php

namespace Pterodactyl\Http\Controllers\Admin\Helionix;

use Illuminate\View\View;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Helionix\DashboardSettingsRequest;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class DashboardController extends Controller
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
        return $this->view->make('admin.helionix.dashboard', [
            'dash_layout' => $this->helionix->get('helionix::helionix:dash_layout', 1),
            'dash_billing_status' => $this->helionix->get('helionix::helionix:dash_billing_status', true),
            'dash_billing_url' => $this->helionix->get('helionix::helionix:dash_billing_url', 'https://flydev.one'),
            'dash_billing_blank' => $this->helionix->get('helionix::helionix:dash_billing_blank', true),
            'dash_website_status' => $this->helionix->get('helionix::helionix:dash_website_status', true),
            'dash_website_url' => $this->helionix->get('helionix::helionix:dash_website_url', 'https://flydev.one'),
            'dash_website_blank' => $this->helionix->get('helionix::helionix:dash_website_blank', true),
            'dash_support_status' => $this->helionix->get('helionix::helionix:dash_support_status', true),
            'dash_support_url' => $this->helionix->get('helionix::helionix:dash_support_url', 'https://dsc.flydev.one'),
            'dash_support_blank' => $this->helionix->get('helionix::helionix:dash_support_blank', true),
            'dash_uptime_status' => $this->helionix->get('helionix::helionix:dash_uptime_status', true),
            'dash_uptime_url' => $this->helionix->get('helionix::helionix:dash_uptime_url', '/uptime'),
            'dash_uptime_blank' => $this->helionix->get('helionix::helionix:dash_uptime_blank', false),
        ]);
    }

    public function store(DashboardSettingsRequest $request)
    {
        foreach ($request->normalize() as $key => $value) {
            $this->helionix->set('helionix::' . $key, $value);
        }

        $this->alert->success('Theme settings have been updated successfully.')->flash();
        return redirect()->route('admin.helionix.dashboard');
    }
}