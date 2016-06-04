<?php

namespace App\Http\Controllers\Server;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ServerLibrary;
use MongoDB\Driver\Server;
use Yajra\Datatables\Datatables;
Use Illuminate\Support\Facades\Log;

class LibraryController extends Controller
{
    public function __construct(Request $request)
    {
        //Check if user has his byond account linked or of the user is an admin
        if($request->user()->user_byond_linked == 0 && $request->user()->cannot('server_library_edit'))
        {
            abort('403','Your byond account is not linked to your forum account.');
        }
    }

    public function index()
    {
        return view('server.library.index');
    }

    public function getShow($book_id)
    {
        $book = ServerLibrary::findOrFail($book_id);
        return view('server.library.show', ['book' => $book]);
    }

    public function getEdit($book_id, Request $request)
    {
        if($request->user()->cannot('server_library_edit'))
        {
            abort('403','You do not have the required permission');
        }
        $book = ServerLibrary::findOrFail($book_id);
        return view('server.library.edit', ['book' => $book]);
    }

    public function postEdit($book_id, Request $request)
    {
        if($request->user()->cannot('server_library_edit'))
        {
            abort('403','You do not have the required permission');
        }
        $this->validate($request,[
            'author' => 'required|max:50',
            'title' => 'required|max:50',
            'content' => 'required',
            'category' => 'required|in:Reference,Non-Fiction,Fiction,Religion,Adult'
        ]);

        $book = ServerLibrary::findOrFail($book_id);
        $book->author = $request->input('author');
        $book->title = $request->input('title');
        $book->content = $request->input('content');
        $book->category = $request->input('category');
        $book->save();

        Log::notice('perm.library.edit - Book has been edited',['user_id' => $request->user()->user_id, 'book_id' => $book->id]);

        return redirect()->route('server.library.index');
    }

    public function delete($book_id, Request $request)
    {
        if($request->user()->cannot('server_library_edit'))
        {
            abort('403','You do not have the required permission');
        }

        $book = ServerLibrary::findOrFail($book_id);
        Log::notice('perm.library.delete - Book has been deleted',['user_id' => $request->user()->user_id, 'book_id' => $book->id, 'book_title' => $book->name, 'book_author' => $book->author]);
        $book->delete();

        return redirect()->route('server.library.index');
    }

    public function getAdd(Request $request)
    {
        if($request->user()->cannot('server_library_edit'))
        {
            abort('403','You do not have the required permission');
        }

        return view('server.library.add');
    }

    public function postAdd(Request $request)
    {
        if($request->user()->cannot('server_library_edit'))
        {
            abort('403','You do not have the required permission');
        }

        $this->validate($request,[
            'author' => 'required|max:50',
            'title' => 'required|max:50',
            'content' => 'required',
            'category' => 'required|in:Reference,Non-Fiction,Fiction,Religion,Adult'
        ]);

        $book = new ServerLibrary();
        $book->author = $request->input('author');
        $book->title = $request->input('title');
        $book->content = $request->input('content');
        $book->category = $request->input('category');
        $book->uploader = $request->user()->user_byond;
        $book->save();

        Log::notice('perm.library.add - Book has been added',['user_id' => $request->user()->user_id, 'book_id' => $book->id]);

        return redirect()->route('server.library.index');
    }

    public function getBookData()
    {
        $books = ServerLibrary::select(['id','title','author','category']);

        return Datatables::of($books)
            ->removeColumn('id')
            ->editColumn('title', '<a href="{{route(\'server.library.show.get\',[\'book\'=>$id])}}">{{$title}}</a>')
            ->addColumn('action','<p><a href="{{route(\'server.library.show.get\',[\'book\'=>$id])}}" class="btn btn-success" role="button">Show</a>  @can(\'server_library_edit\')<a href="{{route(\'server.library.edit.get\',[\'book\'=>$id])}}" class="btn btn-info" role="button">Edit</a><a href="{{route(\'server.library.delete\',[\'book\'=>$id])}}" class="btn btn-danger" role="button">Delete</a>@endcan()</p>')
            ->make();
    }
}
