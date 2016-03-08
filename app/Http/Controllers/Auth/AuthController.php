<?php

//Copyright (c) 2016 "Werner Maisl"
//
//This file is part of the Aurora Webinterface
//
//The Aurora Webinterface is free software: you can redistribute it and/or modify
//it under the terms of the GNU Affero General Public License as
//published by the Free Software Foundation, either version 3 of the
//License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU Affero General Public License for more details.
//
//You should have received a copy of the GNU Affero General Public License
//along with this program. If not, see <http://www.gnu.org/licenses/>.

namespace App\Http\Controllers\Auth;

use DB;
use App\Services\Auth\ForumUserModel;
use Carbon\Carbon;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $username = 'username';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Logs the user in by supplying a username and a login token
     */
    public function sso_server(Request $request)
    {
        $ckey_in = null;
        $token_in = null;
        $location = null;

        //Get the token and the ckey from the request
        $ckey_in = $request->input('ckey');
        $token_in = $request->input('token');
        $location = $request->input('location');
        $user_ip = $_SERVER['REMOTE_ADDR'];
        if($ckey_in == null | $token_in == null) abort(404);

        //Check if a sso entry for the ckey and token exists
        $valid_until = Carbon::now();
        $valid_until->subHours(config('aurora.token_valid_time'));
        $count = DB::connection('server')->table('web_sso')
            ->where('ckey',$ckey_in)
            ->where('token',$token_in)
            ->where('ip',$user_ip)
            ->where('created_at','>',$valid_until->toDateTimeString())
            ->count();
        if($count == 0) abort(404);

        //Check if a user with a linked byond account exists in the forum db
        $user = ForumUserModel::where('user_byond',$ckey_in)->firstOrFail();

        //Auth the user
        Auth::login($user);

        //Delete the sso entry from the db
        DB::connection('server')->table('web_sso')->where('ckey',$ckey_in)->delete();

        //Redirect User to Destination
        switch ($location)
        {
            case "user_dashboard":
                return redirect()->route('user.dashboard');
                break;
            case "contract_overview":
                return redirect()->route('syndie.contracts.index');
                break;
            case "contract_details":
                return redirect()->route('syndie.contracts.show',['contract'=>$request->input('contract')]);
                break;
            default:
                return redirect()->route('user.dashboard');
                break;
        }
    }
}
