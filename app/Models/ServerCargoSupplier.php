<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerCargoSupplier extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'cargo_suppliers';
    protected $primaryKey = 'id';
    public $timestamps = TRUE;
}
