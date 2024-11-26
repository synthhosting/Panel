<?php

namespace Pterodactyl\Models\VeltaStudios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Pterodactyl\Models\Node;

class WingsUpdaterConfiguration extends Model
{
    protected $table = 'vs_wingsupdater_configurations';

    protected $fillable = [
        'node_id', 'method', 'credential', 'passphrase', 'wings_mode', 'ssh_user', 'ssh_port'
    ];

    protected $casts = [
        'node_id' => 'integer',
        'wings_mode' => 'string',
        'ssh_port' => 'integer'
    ];

    public function setCredentialAttribute($value)
    {
        $this->attributes['credential'] = Crypt::encryptString($value);
    }

    public function getCredentialAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            return null;
        }
    }

    public function setPassphraseAttribute($value)
    {
        if ($value) {
            $this->attributes['passphrase'] = Crypt::encryptString($value);
        } else {
            $this->attributes['passphrase'] = null;
        }
    }

    public function getPassphraseAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (DecryptException $e) {
            return null;
        }
    }

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id');
    }
}
