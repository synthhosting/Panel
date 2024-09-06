<?php

namespace Pterodactyl\Http\Requests\Admin\Updater;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class RestoreFormRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'rollback' => 'nullable',
        ];
    }
}
