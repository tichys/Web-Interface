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
            abort('403','You do not have the required permission');

        return view('server.chars.show', ['char' => $char,'char_flavour' => $char_flavour]);
    }


    public function getEditCR(Request $request, $char_id)
    {
        if($request->user()->cannot('ccia_record_edit'))
        {
            abort('403','You do not have the required permission');
        }

        $char = ServerCharacter::findOrFail($char_id);
        $char_flavour = ServerCharacterFlavour::findOrFail($char_id);

        return view('server.chars.edit_cr',['char' => $char,'char_flavour' => $char_flavour]);
    }

    public function postEditCR(Request $request, $char_id)
    {
        if($request->user()->cannot('ccia_record_edit'))
        {
            abort('403','You do not have the required permission');
        }

        $input = $request->all();

        if(!isset($input["records_ccia"]) || $input["records_ccia"] == "" || $input["records_ccia"] == NULL)
        {
            $input["records_ccia"] = "";
        }

        $char_flavour = ServerCharacterFlavour::findOrFail($char_id);
        $char_flavour->records_ccia = $input["records_ccia"];
        $char_flavour->save();
        Log::notice('perm.server.char.editcciarecord - CCIA Record has been edited',['user_id' => $request->user()->user_id, 'char_id' => $char_id]);
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
            ->addColumn('action','<p><a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}" class="btn btn-success" role="button">Show</a>@can(\'ccia_record_edit\')<a href="{{route(\'server.chars.edit.cr.get\',[\'book\'=>$id])}}" class="btn btn-info" role="button">Edit CCIA Record</a>@endcan()</p>')
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


}
