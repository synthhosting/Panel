<?php

namespace Pterodactyl\Http\Requests\Admin\Helionix;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class GeneralSettingsRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'helionix:logo' => 'required|string',
            'helionix:favicon' => 'required|string',
            'helionix:logo_only' => 'required|boolean',
            'helionix:logo_height' => 'required|string',
        ];
    }
}