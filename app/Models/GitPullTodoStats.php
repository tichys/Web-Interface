<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GitPullTodoStats extends Model
{
    protected $connection = 'server';
    protected $table = 'git_pull_todo_stats';
    protected $fillable = ['todo_id', 'ckey', 'status', 'description'];
    protected $primaryKey = 'todo_stat_id';
}
