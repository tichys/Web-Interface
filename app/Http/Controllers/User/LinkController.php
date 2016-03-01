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
        //Check if a linking request already exists (where the deleted_at date is not set)
        $linking_in_progress = DB::connection('server')->table('player_linking')
                ->where('forum_id', '=', $request->user()->user_id)->where('deleted_at','=',NULL)->count() != 0;


        //Check if the linking request is marked as verified
        if ($linking_in_progress == TRUE) {
            //get the linking request
            $linking_request = DB::connection('server')->table('player_linking')
                ->where('forum_id', '=', $request->user()->user_id)->where('deleted_at','=',NULL)->first();

            //Check if the linking request is set to something other than new
            if ($linking_request->status == "confirmed") {
                //If its confirmed write it to the forum db
                $request->user()->user_byond_linked = 1;
                $request->user()->user_byond = $linking_request->player_ckey;
                $request->user()->save();

                //Set the status of the linking request to linked and set the deleted_at date
                DB::connection('server')->table('player_linking')->where('forum_id', '=', $request->user()->user_id)->where('deleted_at','=',NULL)->update(['deleted_at' => date('Y-m-d H:i:s',time()),'status'=>'linked']);

                //Redirect back to the same page to refresh the user data of the logged in user
                return redirect()->route('user.link');

            } elseif ($linking_request->status == "rejected") {
                //If its rejected, delete it. (Just call the cancel function to avoid duplicate code)
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


        //Only add a new linking request if there is no existing one (where the deleted_at date is not set)
        if (DB::connection('server')->table('player_linking')->where('forum_id', '=', $request->user()->user_id)->where('deleted_at','=',NULL)->count() == 0) {

            DB::connection('server')->table('player_linking')
                ->insert([
                    ['forum_id' => $request->user()->user_id,
                        'forum_username_short' => $request->user()->username_clean,
                        'forum_username' => $request->user()->username,
                        'player_ckey' => $request->input("Byond_Username"),
                        'status' => 'new',
                        'created_at' => date('Y-m-d H:i:s',time()),
                        'updated_at' => date('Y-m-d H:i:s',time())
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
        //Set the deleted_at column where the forum_id matches the forum id of the logged in user and the deleted_at column is not set --> Should be only one
        DB::connection('server')->table('player_linking')->where('forum_id', '=', $request->user()->user_id)->where('deleted_at','=',NULL)->update(['deleted_at' => date('Y-m-d H:i:s',time())]);

        return redirect()->route('user.link');
    }
}
