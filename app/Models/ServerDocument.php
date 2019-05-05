<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerDocument extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'documents';
    protected $primaryKey = 'id';
    public $timestamps = TRUE;
}
