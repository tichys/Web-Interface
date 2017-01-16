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
namespace App\Http\Controllers\Server;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ServerCharacter;
use App\Models\ServerCharacterFlavour;
use phpDocumentor\Reflection\Types\Null_;
use Yajra\Datatables\Facades\Datatables;
use Log;

class CharController extends Controller
{

    public function index()
    {
        return view('server.chars.index');
    }

    public function indexAll(Request $request)
    {
        if($request->user()->cannot('server_chars_show'))
        {
            abort('403','You do not have the required permission');
        }

        return view('server.chars.index_all');
    }

    public function getShow($char_id, Request $request)
    {
        $char = ServerCharacter::findOrFail($char_id);
        $char_flavour = ServerCharacterFlavour::findOrFail($char_id);

        //Check if the user can view the chars
        if(!$this->can_view_char($request,$char))
            abort('403','You do not have the required permission to view this character');

        return view('server.chars.show', ['char' => $char,'char_flavour' => $char_flavour, 'can_edit'=> $this->can_edit_char($request,$char)]);
    }

    public function postEditText(Request $request, $char_id)
    {
        $char = ServerCharacter::findOrFail($char_id);
        if(!$this->can_edit_char($request,$char))
            abort('403','You do not have the required permission to edit this record');

        $this->validate($request,[
           'type' => 'bail|required|string|in:records_ccia,records_exploit,records_security,records_medical,records_employment,flavour_general,flavour_head,flavour_face,flavour_eyes,flavour_torso,flavour_arms,flavour_hands,flavour_legs,flavour_feet',
            $request->get('type') => 'bail|required|string'
        ]);

        if($request->input('type') == 'records_ccia' && $request->user()->cannot('server_chars_edit'))
            abort('403','You do not have the required permission to edit this record');

        $type = $request->input('type');

        $char_flavour = ServerCharacterFlavour::findOrFail($char_id);
        $char_flavour->$type = $request->input($type);
        $char_flavour->save();
        Log::notice('perm.server.char.record.edit - Char has been edited',['user_id' => $request->user()->user_id, 'char_id' => $char_id, 'type'=>$type]);
        return redirect()->route('server.chars.show.get',['char_id'=>$char_id]);
    }


    public function getCharDataAll(Request $request)
    {

        if($request->user()->cannot('server_chars_show'))
        {
            abort('403','You do not have the required permission');
        }

        $chars = ServerCharacter::select(['id','name','ckey']);

        return Datatables::of($chars)
            ->removeColumn('id')
            ->editColumn('name', '<a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}">{{$name}}</a>')
            ->addColumn('action','<p><a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}" class="btn btn-success" role="button">Show</a></p>')
            ->make();
    }

    public function getCharDataOwn(Request $request)
    {
        $chars = ServerCharacter::select(['id','name'])->where('ckey',$request->user()->user_byond);

        return Datatables::of($chars)
            ->removeColumn('id')
            ->editColumn('name', '<a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}">{{$name}}</a>')
            ->addColumn('action','<p><a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}" class="btn btn-success" role="button">Show</a></p>')
            ->make();
    }

    public function getCharDataCkey($ckey, Request $request)
    {

        if($request->user()->cannot('server_chars_show'))
        {
            abort('403','You do not have the required permission');
        }

        $chars = ServerCharacter::where('ckey',$ckey)->select(['id','name','ckey']);

        return Datatables::of($chars)
            ->removeColumn('id')
            ->editColumn('name', '<a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}">{{$name}}</a>')
            ->addColumn('action','<p><a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}" class="btn btn-success" role="button">Show</a></p>')
            ->make();
    }

    private function can_view_char(Request $request,$char)
    {
        //Check if the user is the owner of the char
        if($request->user()->user_byond == $char->ckey)
            return true;

        //Check if the user has the permission to view characters
        if($request->user()->can('server_chars_show'))
            return true;

        return false;
    }

    private function can_edit_char(Request $request,$char)
    {
        //Check if the user is the owner of the char
        if($request->user()->user_byond == $char->ckey)
            return true;

        //Check if the user has the permission to view characters
        if($request->user()->can('server_chars_edit'))
            return true;

        return false;
    }
}
