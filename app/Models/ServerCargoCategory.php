<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerCargoCategory extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'cargo_categories';
    protected $primaryKey = 'id';
    public $timestamps = TRUE;
}
