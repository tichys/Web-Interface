<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerCharacterLog extends Model
{
    protected $connection = 'server';
    protected $table = 'characters_log';
    protected $primaryKey = 'id';
    public $timestamps = FALSE;
}
