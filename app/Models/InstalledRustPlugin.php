<?php

namespace Pterodactyl\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstalledRustPlugin extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'installed_rust_plugins';

    /**
     * Cast values to correct type.
     */
    protected $casts = [
        'update' => 'boolean',
    ];

    /**
     * Fields that are mass assignable.
     */
    protected $fillable = [
        'server_id',
        'url',
        'title',
        'name',
        'tags_all',
        'icon_url',
        'author',
        'downloads_shortened',
        'donate_url',
        'version',
        'update',
    ];

    /**
     * Validation rules to assign to this model.
     */
    public static array $validationRules = [
        'server_id' => 'required|exists:servers,id',
        'url' => 'required|url',
        'title' => 'required|string',
        'name' => 'required|string',
        'tags_all' => 'nullable|string',
        'icon_url' => 'nullable|string',
        'author' => 'nullable|string',
        'downloads_shortened' => 'required|string',
        'donate_url' => 'nullable|url',
        'version' => 'required|string',
        'update' => 'boolean',
    ];

    /**
     * Gets information for the server associated with this allocation.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}