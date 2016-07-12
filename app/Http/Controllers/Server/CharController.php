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

    public function indexAll(Request $request)
    {
        if($request->user()->cannot('admin_char_show'))
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

        $this->validate($request,[
            'records_ccia' => 'required'
        ]);

        $char_flavour = ServerCharacterFlavour::findOrFail($char_id);
        $char_flavour->records_ccia = $request->input('records_ccia');
        $char_flavour->save();
        return redirect()->route('server.chars.show.get',['char_id'=>$char_id]);

    }


    public function getCharDataAll(Request $request)
    {

        if($request->user()->cannot('admin_char_show'))
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
        if($request->user()->can('admin_char_show'))
            return true;

        return false;
    }


}
