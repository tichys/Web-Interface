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
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PlayerController extends Controller
{

    public function __construct(Request $request)
    {
        if($request->user()->cannot('admin_players_show'))
        {
            abort('403','You do not have the required permission');
        }
    }

    /**
     * Displays all the players and their whitelists in a laravel datatable
     */
    public function index()
    {
        return view('admin.player.index');
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

        return view('admin.player.show',['player'=>$player,'whitelists'=>$player->get_player_whitelists()]);
    }


    public function addWhitelist($player_id, $whitelist, Request $request)
    {
        if($request->user()->cannot('admin_whitelists_edit'))
        {
            abort('403','You do not have the required permission');
        }

        //Get Server Player
        $player = ServerPlayer::findOrFail($player_id);

        $player->add_player_whitelist_flag($whitelist,$request->user()->username_clean);

        return redirect()->route('admin.players.show', ['player_id' => $player_id]);
    }

    public function removeWhitelist($player_id, $whitelist, Request $request)
    {
        if($request->user()->cannot('admin_whitelists_edit'))
        {
            abort('403','You do not have the required permission');
        }

        //Get Server Player
        $player = ServerPlayer::findOrFail($player_id);

        $player->strip_player_whitelist_flag($whitelist,$request->user()->username_clean);

        return redirect()->route('admin.players.show', ['player_id' => $player_id]);
    }


    public function getPlayerWarningsData($player_id, Request $request)
    {
        if($request->user()->cannot('admin_warnings_show'))
        {
            abort('403','You do not have the required permission');
        }

        $player = ServerPlayer::findOrFail($player_id);

        $warnings = DB::connection('server')
            ->table('warnings')
            ->where('ckey','=',$player->ckey)
            ->select(['id','time','severity','acknowledged','a_ckey','reason']);

        return Datatables::of($warnings)
            ->make();
    }

    public function getPlayerNotesData($player_id, Request $request)
    {
        if($request->user()->cannot('admin_notes_show'))
        {
            abort('403','You do not have the required permission');
        }

        $player = ServerPlayer::findOrFail($player_id);

        $notes = DB::connection('server')
            ->table('notes')
            ->where('ckey','=',$player->ckey)
            ->select(['id','adddate','a_ckey','content']);

        return Datatables::of($notes)
            ->make();

    }

    public function getPlayerData()
    {
        $players = ServerPlayer::select(['id','ckey']);

        return Datatables::of($players)
            ->editColumn('ckey','<a href="{{route(\'admin.players.show\',[\'player_id\'=>$id])}}">{{$ckey}}</a>')
            ->make();
    }
}
