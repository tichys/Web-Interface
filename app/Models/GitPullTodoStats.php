<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GitPullTodoStats extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'git_pull_todo_stats';
    protected $fillable = ['todo_id', 'ckey', 'status', 'description'];
    protected $primaryKey = 'todo_stat_id';
}
