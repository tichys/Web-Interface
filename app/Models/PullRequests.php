<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PullRequests extends Model
{
    protected $connection = 'server';
    protected $table = 'gitpullrequests';
    protected $fillable = ['title', 'body', 'merged_into'];
    protected $primaryKey = 'id';
}
