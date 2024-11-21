<?php

namespace Pterodactyl\Http\Requests\Admin\Helionix;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class AuthenticationSettingsRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'helionix:authentication:title' => 'required|string',
            'helionix:authentication:description' => 'required|string',
            'helionix:authentication:layout' => 'required|numeric',
            'helionix:authentication:register:status' => 'required|boolean',
            // google auth
            'helionix:authentication:google:status' => 'required|boolean',
            'helionix:authentication:google:client_id' => 'nullable|string',
            'helionix:authentication:google:client_secret' => 'nullable|string',
            'helionix:authentication:google:redirect' => 'nullable|string',
            // discord auth
            'helionix:authentication:discord:status' => 'required|boolean',
            'helionix:authentication:discord:client_id' => 'nullable|string',
            'helionix:authentication:discord:client_secret' => 'nullable|string',
            'helionix:authentication:discord:redirect' => 'nullable|string',
            // github auth
            'helionix:authentication:github:status' => 'required|boolean',
            'helionix:authentication:github:client_id' => 'nullable|string',
            'helionix:authentication:github:client_secret' => 'nullable|string',
            'helionix:authentication:github:redirect' => 'nullable|string',
        ];
    }
}