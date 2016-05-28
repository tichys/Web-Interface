<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerLibrary extends Model
{
    protected $connection = 'server';
    protected $table = 'library';
    protected $fillable = ['author', 'title', 'content', 'category', 'uploadtime', 'uploader'];
    protected $primaryKey = 'id';
    public $timestamps = FALSE;
}
