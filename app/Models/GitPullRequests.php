<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GitPullRequests extends Model
{
    protected $connection = 'server';
    protected $table = 'git_pull_requests';
    protected $fillable = ['title', 'body', 'git_id', 'merged_into'];
    protected $primaryKey = 'pull_id';
}
