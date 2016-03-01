<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        //Check if a linking request already exists
        $linking_in_progress = DB::connection('server')->table('player_linking')
                ->where('forum_id', '=', $request->user()->user_id)->count() != 0;


        //Check if the linking request is marked as verified
        if ($linking_in_progress == TRUE) {
            //get the linking request
            $linking_request = DB::connection('server')->table('player_linking')
                ->where('forum_id', '=', $request->user()->user_id)->first();

            //Check if the linking request is set to something other than new
            if ($linking_request->status == "confirmed") {
                //If its confirmed write it to the forum db
                $request->user()->user_byond_linked = 1;
                $request->user()->user_byond = $linking_request->player_ckey;
                $request->user()->save();

                //Then delete the linking request
                $this->cancel($request);

            } elseif ($linking_request->status == "rejected") {
                //If its rjected, delete it. (Just call the cancel function to avoid duplicate code)
                $this->cancel($request);
            }
        }


        return view('user.link.index', array("linking_in_progress" => $linking_in_progress));
    }

    /**
     * @param Request $request
     *
     * Add a new linking request to the db
     * Then redirect the user back to the linking page
     */
    public function add(Request $request)
    {
        $this->validate($request, [
            'Byond_Username' => 'required'
        ]);


        //Only add a new linking request if there is no existing one
        if (DB::connection('server')->table('player_linking')->where('forum_id', '=', $request->user()->user_id)->count() == 0) {

            DB::connection('server')->table('player_linking')
                ->insert([
                    ['forum_id' => $request->user()->user_id,
                        'forum_username_short' => $request->user()->username_clean,
                        'forum_username' => $request->user()->username,
                        'player_ckey' => $request->input("Byond_Username"),
                        'status' => 'new',
                    ]
                ]);

        }

        return redirect()->route('user.link');
    }

    /**
     * Delete all pending linking requests for the user
     *
     * @param Request $request
     */
    public function cancel(Request $request)
    {
        DB::connection('server')->table('player_linking')->where('forum_id', '=', $request->user()->user_id)->delete();

        return redirect()->route('user.link');
    }
}
