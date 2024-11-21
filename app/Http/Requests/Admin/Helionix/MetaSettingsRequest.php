<?php

namespace Pterodactyl\Http\Requests\Admin\Helionix;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class MetaSettingsRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'helionix:meta_logo' => 'required|string',
            'helionix:meta_title' => 'required|string',
            'helionix:meta_description' => 'required|string',
            'helionix:meta_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }
}