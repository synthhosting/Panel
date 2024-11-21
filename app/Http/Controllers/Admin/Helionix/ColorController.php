<?php

namespace Pterodactyl\Http\Controllers\Admin\Helionix;

use Illuminate\View\View;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Helionix\ColorSettingsRequest;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class ColorController extends Controller
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
        return $this->view->make('admin.helionix.color', [
            'color_1' => $this->helionix->get('helionix::helionix:color_1', '#0F121A'),
            'color_2' => $this->helionix->get('helionix::helionix:color_2', '#151923'),
            'color_3' => $this->helionix->get('helionix::helionix:color_3', '#212633'),
            'color_4' => $this->helionix->get('helionix::helionix:color_4', '#2D3340'),
            'color_5' => $this->helionix->get('helionix::helionix:color_5', '#3A3f4D'),
            'color_6' => $this->helionix->get('helionix::helionix:color_6', '#474D5B'),
            'color_console' => $this->helionix->get('helionix::helionix:color_console', '#151923'),
            'color_editor' => $this->helionix->get('helionix::helionix:color_editor', '#151923'),
            'button_primary' => $this->helionix->get('helionix::helionix:button_primary', '#2DCE89'),
            'button_primary_hover' => $this->helionix->get('helionix::helionix:button_primary_hover', '#20AB6F'),
            'button_secondary' => $this->helionix->get('helionix::helionix:button_secondary', '#A9B0C1'),
            'button_secondary_hover' => $this->helionix->get('helionix::helionix:button_secondary_hover', '#9EA5B6'),
            'button_danger' => $this->helionix->get('helionix::helionix:button_danger', '#FF3A62'),
            'button_danger_hover' => $this->helionix->get('helionix::helionix:button_danger_hover', '#EE2A51'),
            'color_h1' => $this->helionix->get('helionix::helionix:color_h1', '#FFFFFF'),
            'color_svg' => $this->helionix->get('helionix::helionix:color_svg', '#FFFFFF'),
            'color_label' => $this->helionix->get('helionix::helionix:color_label', '#F2F2F2'),
            'color_input' => $this->helionix->get('helionix::helionix:color_input', '#F2F2F2'),
            'color_p' => $this->helionix->get('helionix::helionix:color_p', '#EBEBEB'),
            'color_a' => $this->helionix->get('helionix::helionix:color_a', '#EBEBEB'),
            'color_span' => $this->helionix->get('helionix::helionix:color_span', '#E2E2E2'),
            'color_code' => $this->helionix->get('helionix::helionix:color_code', '#E2E2E2'),
            'color_strong' => $this->helionix->get('helionix::helionix:color_strong', '#E2E2E2'),
            'color_invalid' => $this->helionix->get('helionix::helionix:color_invalid', '#FF3A62'),
        ]);
    }

    public function store(ColorSettingsRequest $request)
    {
        foreach ($request->normalize() as $key => $value) {
            $this->helionix->set('helionix::' . $key, $value);
        }

        $this->alert->success('Theme settings have been updated successfully.')->flash();
        return redirect()->route('admin.helionix.color');
    }
}