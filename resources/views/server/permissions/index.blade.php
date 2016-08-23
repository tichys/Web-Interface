{{--Copyright (c) 2016 "Werner Maisl"--}}

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

@section('styles')
    <link href="{{ asset('/assets/css/datatables.bootstrap.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Search Filter</div>
            <div class="panel-body">
                {{Form::open(array('route' => 'server.permissions.index','method' => 'post','class' =>'form-inline')) }}
                {{Form::token()}}
                {{Form::hidden('s','1')}}

                {{Form::bsText('ckey')}}
                {{Form::bsText('rank')}}
                {{Form::submit('Submit', array('class'=>'btn btn-default'))}}

                {{ Form::close() }}
                @can('admin_server_permissions_edit')<p><a href="{{route('server.library.add.post')}}" class="btn btn-success" role="button">Add new Permission</a></p>@endcan()
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ckey</th>
                <th>rank</th>
                @foreach($flags as $flag => $value)
                <th>{{$flag}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($admins as $admin)
                <tr>
                    <td>{{$admin->ckey}}</td>
                    <td>{{$admin->rank}}</td>
                    @foreach($flags as $flag => $value)
                        @if($admin->has_flag($flag))
                            <td>
                                <a href="{{route('servers.permissions.flag.remove',['permission_id'=>$admin->id,'flag'=>$flag])}}"
                                   class="btn btn-success" role="button"><span class="glyphicon glyphicon-ok"></span></a>
                            </td>
                        @else()
                            <td>
                                <a href="{{route('servers.permissions.flag.add',['permission_id'=>$admin->id,'flag'=>$flag])}}"
                                   class="btn btn-danger" role="button"><span class="glyphicon glyphicon-remove"></span></a>
                            </td>
                        @endif()
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/datatables.bootstrap.js') }}"></script>
@endsection