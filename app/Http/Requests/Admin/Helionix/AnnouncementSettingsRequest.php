<?php

namespace Pterodactyl\Http\Requests\Admin\Helionix;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class AnnouncementSettingsRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'helionix:announcements_status' => 'required|boolean',
        ];
    }
}