{{--Copyright (c) 2018 "Werner Maisl"--}}

{{--This file is part of the Aurora Webinterface--}}

{{--The Aurora Webinterface is free software: you can redistribute it and/or modify--}}
{{--it under the terms of the GNU Affero General Public License as--}}
{{--published by the Free Software Foundation, either version 3 of the--}}
{{--License, or (at your option) any later version.--}}

{{--This program is distributed in the hope that it will be useful,--}}
{{--but WITHOUT ANY WARRANTY; without even the implied warranty of--}}
{{--MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the--}}
{{--GNU Affero General Public License for more details.--}}

{{--You should have received a copy of the GNU Affero General Public License--}}
{{--along with this program. If not, see <http://www.gnu.org/licenses/>.<!DOCTYPE html>--}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('components.formerrors')
            <div class="panel panel-default">
                <table class="table">
                    <tbody>
                    <tr>
                        <td><b>Author:</b></td>
                        <td>{{$news->author}}</td>
                    </tr>
                    <tr>
                        <td><b>Channel:</b></td>
                        <td>{{$news->channel->name}}</td>
                    </tr>
                    <tr>
                        <td><b>Message Type:</b></td>
                        <td>{{$news->message_type}}</td>
                    </tr>
                    <tr>
                        <td><b>Timestamp:</b></td>
                        <td>{{$news->time_stamp}}</td>
                    </tr>
                    <tr>
                        <td><b>Created By:</b></td>
                        <td>{{$news->created_by}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('components.formerrors')
            <div class="panel panel-default">
                <div class="panel-heading">News</div>

                <div class="panel-body">
                    {{$news->body}}
                </div>
                @can('server_news_edit')
                <div class="panel-footer">
                    <a href="{{route('server.news.edit.get',['news_id'=>$news->id])}}" class="btn btn-info" role="button">Edit</a>
                    <a href="{{route('server.news.delete',['news_id'=>$news->id])}}" class="btn btn-danger" role="button">Delete</a>
                </div>
                @endcan()
            </div>
        </div>
    </div>
</div>
@endsection