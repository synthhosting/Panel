<?php

namespace Pterodactyl\Http\Requests\Admin\Helionix;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class DashboardSettingsRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'helionix:dash_layout' => 'required|numeric',
            'helionix:dash_billing_status' => 'required|boolean',
            'helionix:dash_billing_url' => 'required|string',
            'helionix:dash_billing_blank' => 'required|boolean',
            'helionix:dash_website_status' => 'required|boolean',
            'helionix:dash_website_url' => 'required|string',
            'helionix:dash_website_blank' => 'required|boolean',
            'helionix:dash_support_status' => 'required|boolean',
            'helionix:dash_support_url' => 'required|string',
            'helionix:dash_support_blank' => 'required|boolean',
            'helionix:dash_uptime_status' => 'required|boolean',
            'helionix:dash_uptime_url' => 'required|string',
            'helionix:dash_uptime_blank' => 'required|boolean',
        ];
    }
}