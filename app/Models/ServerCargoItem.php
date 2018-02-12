<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerCargoItem extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'cargo_items';
    protected $primaryKey = 'id';
    public $timestamps = TRUE;
}
