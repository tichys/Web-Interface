<?php

namespace App\Http\Controllers\Git;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HookController extends Controller
{
    public function index(Request $request)
    {
        $event = $request->header("X-GitHub-Event");

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
            $clean_array = array();

            //Format ToDo List Properly
            foreach ($todo_array as $key=>$value)
            {
                $text = trim($value," -\t\n\r\0\x0B");
                if ($text != "")
                    $clean_array[] = $text;
            }

            //Check if a entry with that pull request id alraedy exists

            //If so, then replace the current entry

            //If not then just add it to the db

            echo "<pre>".var_dump($clean_array)."</pre>";
        } else {
            abort(501);
        }

    }
}
