<?php

namespace App\Http\Controllers\Server;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ServerCharacter;
use App\Models\ServerCharacterFlavour;
use Yajra\Datatables\Facades\Datatables;

class CharController extends Controller
{
    public function __construct(Request $request)
    {

    }

    public function index()
    {
        return view('server.chars.index');
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

    public function getCharData(Request $request)
    {
        if($request->user()->can('admin_char_show'))
        {
            $chars = ServerCharacter::select(['id','name','ckey']);

            return Datatables::of($chars)
                ->removeColumn('id')
                ->editColumn('name', '<a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}">{{$name}}</a>')
                ->addColumn('action','<p><a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}" class="btn btn-success" role="button">Show</a></p>')
                ->make();
        }
        else
        {
            $chars = ServerCharacter::select(['id','name'])->where('ckey',$request->user()->user_byond);

            return Datatables::of($chars)
                ->removeColumn('id')
                ->editColumn('name', '<a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}">{{$name}}</a>')
                ->addColumn('action','<p><a href="{{route(\'server.chars.show.get\',[\'char\'=>$id])}}" class="btn btn-success" role="button">Show</a></p>')
                ->make();
        }
    }

    private function can_view_char(Request $request,$char)
    {
        //Check if the user is the owner of the char
        if($request->user()->user_byond == $char->ckey)
            return true;

        //Check if the user has the permission to view characters
        if($request->user()->can('admin_char_show'))
            return true;

        return false;
    }


}
