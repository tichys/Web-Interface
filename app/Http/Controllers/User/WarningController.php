<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WarningController extends Controller
{
    /**
     * Displays the users Warnings to the User
     *
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('byond_linked')) {
            abort(403, 'Byond Account not linked');
        }

        //Get the users warnings from the Database
        $warnings = DB::connection('server')->table('warnings')->where('ckey', '=', $request->user()->user_byond)->where('visible', '=', '1')->get();

        //Transform the timestamps to Ago strings
        foreach ($warnings as $warning) {
            $carbon = new Carbon($warning->time);
            $warning->diff = $carbon->diffForHumans();
        }

        //Display the View
        return view('user/warnings/index', ["warnings" => $warnings]);
    }
}
