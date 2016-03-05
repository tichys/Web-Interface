<?php
/**
 * Copyright (c) 2016 "Werner Maisl"
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

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MongoDB\Driver\Server;
use Yajra\Datatables\Datatables;
use App\Models\ServerPlayer;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WhitelistController extends Controller
{

    public function __construct()
    {
        if(Auth::user()->cannot('admin_whitelists_show'))
        {
            abort('403','You do not have the required permission');
        }
    }

    /**
     * Displays all the players and their whitelists in a laravel datatable
     */
    public function index()
    {
        return view('admin.whitelist.index');
    }

    /**
     * Shows the whitelist Status of a single player
     * Displays a form to add / remove a whitelist
     *
     * @param         $player_id
     * @param Request $request
     */
    public function show($player_id, Request $request)
    {
        $player = ServerPlayer::findOrFail($player_id);

        return view('admin.whitelist.show',['player'=>$player,'whitelists'=>$player->get_player_whitelists()]);
    }


    public function add($player_id, $whitelist, Request $request)
    {
        if(Auth::user()->cannot('admin_whitelists_edit'))
        {
            abort('403','You do not have the required permission');
        }

        //Get Server Player
        $player = ServerPlayer::findOrFail($player_id);

        $player->add_player_whitelist_flag($whitelist);

        return redirect()->route('admin.whitelist.show', ['player_id' => $player_id]);
    }

    public function remove($player_id, $whitelist, Request $request)
    {
        if(Auth::user()->cannot('admin_whitelists_edit'))
        {
            abort('403','You do not have the required permission');
        }

        //Get Server Player
        $player = ServerPlayer::findOrFail($player_id);

        $player->strip_player_whitelist_flag($whitelist);

        return redirect()->route('admin.whitelist.show', ['player_id' => $player_id]);
    }


    public function getWhitelistData()
    {
        $players = ServerPlayer::select(['id','ckey','whitelist_status']);

        return Datatables::of($players)
            ->editColumn('ckey','<a href="{{route(\'admin.whitelist.show\',[\'player_id\'=>$id])}}">{{$ckey}}</a>')
            ->make();
    }
}
