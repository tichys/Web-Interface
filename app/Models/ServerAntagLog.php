<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerAntagLog extends Model
{
    protected $connection = 'server';
    protected $table = 'antag_log';
    protected $primaryKey = 'id';
    public $timestamps = FALSE;

    public function character(){
        return $this->belongsTo('App\Models\ServerCharacter','char_id');
    }
}
