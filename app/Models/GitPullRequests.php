<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GitPullRequests extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'git_pull_requests';
    protected $fillable = ['title', 'body', 'git_id', 'merged_into'];
    protected $primaryKey = 'pull_id';
}
