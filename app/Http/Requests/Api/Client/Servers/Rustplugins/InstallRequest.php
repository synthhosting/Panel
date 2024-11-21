<?php

namespace Pterodactyl\Http\Requests\Api\Client\Servers\Rustplugins;

use Pterodactyl\Models\Permission;
use Pterodactyl\Contracts\Http\ClientPermissionsRequest;
use Pterodactyl\Http\Requests\Api\Client\ClientApiRequest;

class InstallRequest extends ClientApiRequest implements ClientPermissionsRequest
{
    public function permission(): string
    {
        return Permission::ACTION_RUSTPLUGINS_MANAGE;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'directory' => 'required|string',
            'url' => 'required|string',
            'data' => 'nullable|array',
        ];
    }
}