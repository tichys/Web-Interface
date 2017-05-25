<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class GitPullRequests extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'git_pull_requests';
    protected $fillable = ['title', 'body', 'git_id', 'merged_into'];
    protected $primaryKey = 'pull_id';

    public function getWorkingAttribute(){
        $query = DB::connection('server')->table('git_pull_todos')
            ->leftjoin('git_pull_todo_stats','git_pull_todo_stats.todo_id','=','git_pull_todos.todo_id')
            ->groupBy('git_pull_todo_stats.status','git_pull_todo_stats.todo_id')
            ->where('git_pull_todo_stats.status','working')
            ->where('git_pull_todos.pull_id',$this->pull_id)
            ->whereNull('git_pull_todos.deleted_at')
            ->select('git_pull_todo_stats.status','git_pull_todo_stats.todo_id', 'git_pull_todos.pull_id')
            ->get();
        return $query->count();
    }

    public function getBrokenAttribute(){
        $query = DB::connection('server')->table('git_pull_todos')
            ->leftjoin('git_pull_todo_stats','git_pull_todo_stats.todo_id','=','git_pull_todos.todo_id')
            ->groupBy('git_pull_todo_stats.status','git_pull_todo_stats.todo_id')
            ->where('git_pull_todo_stats.status','broken')
            ->where('git_pull_todos.pull_id',$this->pull_id)
            ->whereNull('git_pull_todos.deleted_at')
            ->select('git_pull_todo_stats.status','git_pull_todo_stats.todo_id', 'git_pull_todos.pull_id')
            ->get();
        return $query->count();
    }

    public function getUntestedAttribute(){
        $query = DB::connection('server')->table('git_pull_todos')
            ->leftjoin('git_pull_todo_stats','git_pull_todo_stats.todo_id','=','git_pull_todos.todo_id')
            ->whereNull('git_pull_todo_stats.status')
            ->whereNull('git_pull_todos.deleted_at')
            ->where('git_pull_todos.pull_id',$this->pull_id)
            ->groupBy('git_pull_todos.todo_id')
            ->select('git_pull_todos.todo_id', 'git_pull_todos.pull_id', 'git_pull_todos.description')
            ->get();
        return $query->count();
    }
}
