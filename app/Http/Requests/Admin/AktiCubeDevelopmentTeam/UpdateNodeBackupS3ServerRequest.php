<?php

namespace Pterodactyl\Http\Requests\Admin\AktiCubeDevelopmentTeam;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;
use Pterodactyl\Models\NodeBackupS3Server;

class UpdateNodeBackupS3ServerRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return NodeBackupS3Server::getRules();
    }
}
