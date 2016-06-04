<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerCharacterFlavour extends Model
{
    protected $connection = 'server';
    protected $table = 'characters_flavour';
    protected $primaryKey = 'char_id';
    public $timestamps = FALSE;
}
