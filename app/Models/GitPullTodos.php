<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GitPullTodos extends Model
{
    protected $connection = 'server';
    protected $table = 'git_pull_todos';
    protected $fillable = ['pull_id', 'number', 'description'];
    protected $primaryKey = 'todo_id';
}
