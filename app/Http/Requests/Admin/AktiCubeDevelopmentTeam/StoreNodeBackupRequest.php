<?php

namespace Pterodactyl\Http\Requests\Admin\AktiCubeDevelopmentTeam;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;
use Pterodactyl\Models\NodeBackup;

class StoreNodeBackupRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return NodeBackup::$validationRules;
    }
}
