<?php

namespace Pterodactyl\Http\Requests\Admin\Helionix;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class UptimeSettingsRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'helionix:uptime_nodes_status' => 'required|boolean',
            'helionix:uptime_nodes_unit' => 'required|in:percent,mb,gb,tb,none',
        ];
    }
}