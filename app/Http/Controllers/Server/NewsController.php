<?php
/**
 * Copyright (c) 2018 "Werner Maisl"
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
use App\Models\ServerNewsChannel;
use App\Models\ServerNewsStory;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            //If the users byond account is not linked and he doesnt have permission to edit the library -> Abort
            if ($request->user()->user_byond_linked == 0)
                abort('403', 'Your byond account is not linked to your forum account.');
            if ($request->user()->cannot('server_news_show'))
                abort('403', 'You do not have the required permission.');
            return $next($request);
        });
    }

    public function index()
    {
        return view('server.news.index');
    }

    public function getShow($news_id, Request $request)
    {
        $news = ServerNewsStory::findOrFail($news_id);

        return view('server.news.show', ['news' => $news]);
    }

    public function getEdit($news_id, Request $request)
    {
        if ($request->user()->cannot('server_news_edit'))
            abort('403', 'You do not have the required permission');

        $news = ServerNewsStory::findOrFail($news_id);
        $channels = ServerNewsChannel::select('id', 'name')->get();
        $channel_array = [];
        foreach($channels as $channel)
            $channel_array[$channel->id] = $channel->name;

        return view('server.news.edit', ['news' => $news, 'channels' => $channel_array]);
    }

    public function postEdit($news_id, Request $request)
    {
        if ($request->user()->cannot('server_news_edit'))
            abort('403', 'You do not have the required permission');

        $news = ServerNewsStory::findOrFail($news_id);

        $this->validate($request, [
            'author' => 'required|max:50',
            'body' => 'required',
            'message_type' => 'required',
            'channel_id' => 'required|exists:server.news_channels,id',
            'timestamp' => 'required|date|after:+440years'
        ]);

        $news->author = htmlspecialchars($request->input('author'));
        $news->body = htmlspecialchars($request->input('body'));
        $news->message_type = htmlspecialchars($request->input('message_type'));
        $news->channel_id = $request->input('channel_id');
        $news->time_stamp = $request->input('timestamp');
        $news->save();

        Log::notice('perm.news.edit - News has been edited', ['user_id' => $request->user()->user_id, 'news_id' => $news->id]);

        return redirect()->route('server.news.show.get', ['book_id' => $news_id]);
    }

    public function delete($news_id, Request $request)
    {
        if ($request->user()->cannot('server_news_edit'))
            abort('403', 'You do not have the required permission');

        $news = ServerNewsStory::findOrFail($news_id);
        Log::notice('perm.news.delete - News has been deleted', ['user_id' => $request->user()->user_id, 'newd_id' => $news->id]);
        $news->delete();

        return redirect()->route('server.news.index');
    }

    public function getAdd(Request $request)
    {
        if ($request->user()->cannot('server_news_edit'))
            abort('403', 'You do not have the required permission');

        $channels = ServerNewsChannel::select('id', 'name')->get();
        $channel_array = [];
        foreach($channels as $channel)
            $channel_array[$channel->id] = $channel->name;

        return view('server.news.add', [ 'channels' => $channel_array]);
    }

    public function postAdd(Request $request)
    {
        if ($request->user()->cannot('server_news_edit'))
            abort('403', 'You do not have the required permission');

        $this->validate($request, [
            'author' => 'required|max:50',
            'body' => 'required',
            'message_type' => 'required',
            'channel_id' => 'required|exists:server.news_channels,id',
            'timestamp' => 'required|date|after:+440years'
        ]);

        $news = new ServerNewsStory();
        $news->author = htmlspecialchars($request->input('author'));
        $news->body = htmlspecialchars($request->input('body'));
        $news->message_type = htmlspecialchars($request->input('message_type'));
        $news->channel_id = $request->input('channel_id');
        $news->time_stamp = $request->input('timestamp');
        $news->created_by = $request->user()->user_byond;
        $news->save();

        Log::notice('perm.news.add - News has been added', ['user_id' => $request->user()->user_id, 'news_id' => $news->id]);

        return redirect()->route('server.news.show.get', ['news_id' => $news->id]);
    }

    public function getNewsData()
    {
        $news = ServerNewsStory::with('channel')->select(['id', 'body', 'channel_id', 'author'])->get();
        return Datatables::of($news)
            ->removeColumn('channel_id')
            ->addColumn('channel', function (ServerNewsStory $story) {
                return $story->channel->name;
            })
            ->editColumn('body', '{{str_limit($body,50)}}')
            ->addColumn('action', '<div class="btn-group" role="group"><a href="{{route(\'server.news.show.get\',[\'news_id\'=>$id])}}" class="btn btn-success" role="button">Show</a>  @can(\'server_news_edit\')<a href="{{route(\'server.news.edit.get\',[\'news_id\'=>$id])}}" class="btn btn-info" role="button">Edit</a><a href="{{route(\'server.news.delete\',[\'news_id\'=>$id])}}" class="btn btn-danger" role="button">Delete</a>@endcan()</div>')
            ->rawColumns([0, 3])
            ->make();
    }
}
