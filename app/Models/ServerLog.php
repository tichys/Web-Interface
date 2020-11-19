<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerLog extends Model
{
    protected $table = 'server_logfiles';
    protected $connection = 'wi';
    protected $fillable = ['filename','logdate','gameid'];
}
