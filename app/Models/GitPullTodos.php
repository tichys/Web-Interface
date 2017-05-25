<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GitPullTodos extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'git_pull_todos';
    protected $fillable = ['pull_id', 'number', 'description'];
    protected $primaryKey = 'todo_id';

    public function getWorkingAttribute(){
        return GitPullTodoStats::where('todo_id',$this->todo_id)->where('status','working')->count();
    }

    public function getBrokenAttribute(){
        return GitPullTodoStats::where('todo_id',$this->todo_id)->where('status','broken')->count();
    }
}
