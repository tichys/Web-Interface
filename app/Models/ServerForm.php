<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerForm extends Model
{
    protected $connection = 'server';
    protected $table = 'forms';
    protected $fillable = ['name', 'department', 'data', 'info'];
    protected $primaryKey = 'form_id';
    public $timestamps = FALSE;
}
