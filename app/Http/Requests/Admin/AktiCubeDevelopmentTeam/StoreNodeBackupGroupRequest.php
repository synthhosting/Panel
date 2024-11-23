<?php

namespace Pterodactyl\Http\Requests\Admin\AktiCubeDevelopmentTeam;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;
use Pterodactyl\Models\NodeBackupGroup;

class StoreNodeBackupGroupRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return NodeBackupGroup::getRules();
    }
}
