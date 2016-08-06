<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerCharacter extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'characters';
    protected $primaryKey = 'id';
    public $timestamps = FALSE;

    public function cciaactions()
    {
        return $this->belongsToMany(CCIAAction::class,'ccia_action_char','char_id','action_id');
    }
}
