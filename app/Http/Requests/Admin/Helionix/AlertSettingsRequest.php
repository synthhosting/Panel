<?php

namespace Pterodactyl\Http\Requests\Admin\Helionix;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class AlertSettingsRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'helionix:alert_type' => 'required|in:information,update,error,warning,disable',
            'helionix:alert_clossable' => 'required|boolean',
            'helionix:alert_message' => 'required|string',
            'helionix:alert_color_information' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:alert_color_update' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:alert_color_warning' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:alert_color_error' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }
}