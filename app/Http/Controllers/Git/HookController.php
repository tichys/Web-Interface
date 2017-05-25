<?php
/**
 * Copyright (c) 2017 "Werner Maisl"
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

class HookController extends Controller
{
    public function index(Request $request)
    {
        $event = $request->header("X-GitHub-Event");
        $sign = $request->header("X-Hub-Signature");

        //Verify Github Signature
        if(!$sign)
            abort("400","No Signature Provided");

        if(!extension_loaded("hash"))
            abort("500","Missing 'hash' extension to check the secret code validity.");

        list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + array('', '');
        if (!in_array($algo, hash_algos(), TRUE)) {
            abort("500","Hash algorithm '$algo' is not supported.");
        }

        if (!hash_equals($hash,hash_hmac($algo, $request->getContent(), config("aurora.github_hook_secret")))) {
            abort(401,"Secret does not match");
        }

        if($event == "ping"){
            return "pong";
        } else if($event == "pull_request"){
            $action = $request->input("action");
            $pull_request = $request->input("pull_request");
            $title = $pull_request["title"];
            $body = $pull_request["body"];
            $number = $pull_request["number"];
            $state = $pull_request["state"]; //Needs to be closed to be added to the db and must not exist in the db already
            $html_url = $pull_request["html_url"]; //Url to the Pull Request
            $merged = $pull_request["merged"]; //If the pull request has been merged
            $merged_into = $pull_request["base"]["ref"]; //Branch the pull request is targeting

            //Strip the list from the body
            $todo_string = substr($body,strpos($body,"[TODO]")+9); //Strip everything before the start tag
            $todo_string = substr($todo_string,0,strpos($todo_string,"[/TODO]"));
            $todo_array = explode("\r\n",$todo_string);

            //Check if it targets development ?

            //Check if a entry with that pull request id alraedy exists
            $db_pull = GitPullRequests::where("git_id",$number)->first();
            if($db_pull == NULL) //If not then just add it to the db
            {
                $db_pull = new GitPullRequests();
                $db_pull->title = $title;
                $db_pull->body = $body;
                $db_pull->git_id = $number;
                $db_pull->merged_into = $merged_into;
                $db_pull->save();
                echo "new";
            }
            else //If so, then replace the current entry
            {
                $db_pull->title = $title;
                $db_pull->body = $body;
                $db_pull->git_id = $number;
                $db_pull->merged_into = $merged_into;
                $db_pull->save();

                //Drop all the current Pull ToDos
                GitPullTodos::where('pull_id',$db_pull->pull_id)->delete();
                echo "update ".$db_pull->pull_id;
            }

            $clean_todo_array = array();
            //Format ToDo List Properly for it to be mass inserted
            $i = 1;
            foreach ($todo_array as $key=>$value)
            {
                $text = trim($value," -\t\n\r\0\x0B");
                if ($text != "")
                    $clean_todo_array[] = ["pull_id"=>$db_pull->pull_id,"number"=>$i,"description"=>$text];
                $i++;
            }


            GitPullTodos::insert($clean_todo_array);

//            echo "<pre>".var_dump($db_pull)."</pre><hr>";
//            echo "<pre>".var_dump($clean_todo_array)."</pre><hr>";
        } else {
            abort(501);
        }

    }
}
