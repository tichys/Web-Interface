<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerPlayer extends Model
{
    protected $connection = 'server';
    protected $table = 'player';
    protected $fillable = ['ckey','ip','lastadminrank','whitelist_status'];
    protected $primaryKey = 'id';
    protected $dates = ['firstseen','lastseen'];
}
