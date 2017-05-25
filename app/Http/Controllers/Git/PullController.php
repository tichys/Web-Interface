<?php
/**
 * Copyright (c) 2016-2017 "Werner Maisl"
 *
 * This file is part of Aurorastation-Wi
 * Aurorastation-Wi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace App\Http\Controllers\Git;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GitPullRequests;
use App\Models\GitPullTodos;
use App\Models\GitPullTodoStats;
use Yajra\Datatables\Datatables;

class PullController extends Controller
{
    public function __construct()
    {
        $this->middleware(function($request, $next){
            //If the users byond account is not linked and he doesnt have permission to edit the library -> Abort
            if($request->user()->user_byond_linked == 0)
            {
                abort('403','Your byond account is not linked to your forum account.');
            }
            return $next($request);
        });
    }

    /**
     * Gets all the pull requests
     * Displays the number of todos for each pull requess
     */
    public function index()
    {
        return view("server.git.index");
    }

    public function getPullData()
    {
        $git_pulls = GitPullRequests::select(['pull_id','git_id','title'])->where('merged_into',config('aurora.github_dev_branch'));

        return Datatables::of($git_pulls)
            ->removeColumn('pull_id')
            ->editColumn('git_id','<a href="{{route(\'server.git.pull.get\',[\'pull_id\'=>$pull_id])}}">{{$git_id}}</a>')
            ->addColumn('stats',function(GitPullRequests $pull){
                return '<span class="label label-success">'.$pull->working.'</span> <span class="label label-danger">'.$pull->broken.'</span> <span class="label label-info">'.$pull->untested.'</span>';
            })
            ->rawColumns([0, 2])
            ->make();
    }

    /**
     * Displays a single pull request
     * Shows the individual todos for the pull request
     * Shows some stats for the todos
     *
     * @param $pull_id
     */
    public function getPull($pull_id)
    {
        $git_pull = GitPullRequests::findOrFail($pull_id);

        return view("server.git.pull",["pull"=>$git_pull]);
    }

    public function getTodoData($pull_id)
    {
        $git_todos = GitPullTodos::select(['todo_id','number','description'])->where('pull_id',$pull_id)->get();
        return Datatables::of($git_todos)
            ->removeColumn('todo_id')
            ->editColumn('number','<a href="{{route(\'server.git.todo.get\',[\'todo_id\'=>$todo_id])}}">{{$number}}</a>')
            //->addColumn('stats','asf')
            ->addColumn('stats',function(GitPullTodos $todo){
                return '<span class="label label-success">'.$todo->working.'</span> <span class="label label-danger">'.$todo->broken.'</span>';
            })
            ->rawColumns([0, 2])
            ->make();
    }

    /**
     * Shows the details and comments of a single todo
     *
     * @param $todo_id
     */
    public function getTodo($todo_id)
    {
        $git_todo = GitPullTodos::findOrFail($todo_id);
        return view("server.git.todo",["todo"=>$git_todo]);
    }

    public function getTodoCommentData($todo_id)
    {
        $git_todo_comments = GitPullTodoStats::select('todo_stat_id','ckey','status','description')->where("todo_id",$todo_id);

        return Datatables::of($git_todo_comments)
            ->make();
    }

    /**
     * Comments on a specific ToDo
     *
     * @param         $todo_id
     * @param Request $request
     */
    public function postTodoComment($todo_id, Request $request)
    {
        $this->validate($request,[
            'description' => 'required',
            'status' => 'required|in:working,broken,notice'
        ]);

        $todo = GitPullTodos::findOrFail($todo_id);

        $comment = new GitPullTodoStats();
        $comment->todo_id = $todo->todo_id;
        $comment->ckey = $request->user()->user_byond;
        $comment->description = $request->input("description");
        $comment->status = $request->input("status" );
        $comment->save();

        return redirect()->route('server.git.todo.get',['todo_id'=>$todo_id]);
    }
}
