<?php

namespace Pterodactyl\Http\Requests\Admin\Helionix;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class ServerSettingsRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'helionix:layout_console' => 'required|numeric',
            'helionix:bar_cpu' => 'required|boolean',
            'helionix:bar_memory' => 'required|boolean',
            'helionix:bar_disk' => 'required|boolean',
        ];
    }
}