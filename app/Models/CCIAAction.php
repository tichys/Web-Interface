<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CCIAAction extends Model
{
    use SoftDeletes;

    protected $dates      = ['deleted_at','expires_at'];
    protected $connection = 'server';
    protected $table      = 'ccia_actions';
    protected $fillable   = ['type', 'issuedby', 'details', 'url'];
    protected $primaryKey = 'id';

    public function characters()
    {
        return $this->belongsToMany(ServerCharacter::class,'ccia_action_char','action_id','char_id');
    }
}
