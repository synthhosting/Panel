<?php

namespace Pterodactyl\Http\Requests\Admin\Updater;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class UpdateFormRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'url' => 'required_without:archive_file|nullable|url:https',
            'archive_file' => 'required_without:url|nullable|file|mimetypes:application/zip,application/gzip',
        ];
    }
}
