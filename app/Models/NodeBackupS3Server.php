<?php

namespace Pterodactyl\Models;

class NodeBackupS3Server extends Model
{
    public const RESOURCE_NAME = 'nodebackupsserver';

    protected $table = 'node_backup_s3_servers';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'default_region' => 'string',
        'access_key_id' => 'string',
        'secret_access_key' => 'string',
        'bucket' => 'string',
        'endpoint' => 'string',
        'max_part_size' => 'integer',
        'presigned_url_lifespan' => 'integer',
        'use_path_style_endpoint' => 'boolean',
        'use_accelerate_endpoint' => 'boolean',
    ];

    public static array $validationRules = [
        'name' => 'required|string',
        'description' => 'nullable|string',
        'default_region' => 'required|string',
        'access_key_id' => 'required|string',
        'secret_access_key' => 'required|string',
        'bucket' => 'required|string',
        'endpoint' => 'required|string',
        'max_part_size' => 'integer',
        'presigned_url_lifespan' => 'integer',
        'use_path_style_endpoint' => 'boolean',
        'use_accelerate_endpoint' => 'boolean',
    ];

    public function toAdapterConfig(): array
    {
        return [
            'adapter' => 's3',
            'region' => $this->default_region,
            'key' => $this->access_key_id,
            'secret' => $this->secret_access_key,
            'bucket' => $this->bucket,
            'prefix' => $this->bucket,
            'endpoint' => $this->endpoint,
            'use_path_style_endpoint' => $this->use_path_style_endpoint,
            'use_accelerate_endpoint' => $this->use_accelerate_endpoint,
            'storage_class' => null,
            'version' => 'latest',
            'credentials' => [
                'key' => $this->access_key_id,
                'secret' => $this->secret_access_key,
            ],
        ];
    }
}
