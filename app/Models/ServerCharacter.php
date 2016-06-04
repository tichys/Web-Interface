<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerCharacter extends Model
{
    protected $connection = 'server';
    protected $table = 'characters';
    protected $primaryKey = 'id';
    public $timestamps = FALSE;
}
