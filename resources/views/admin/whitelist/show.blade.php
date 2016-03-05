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

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">User Details</div>

                    <div class="panel-body">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td>ID</td>
                                <td>{{$player->id}}</td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td>{{$player->ckey}}</td>
                            </tr>
                            <tr>
                                <td>First Seen</td>
                                <td>{{$player->firstseen}}</td>
                            </tr>
                            <tr>
                                <td>Last Seen</td>
                                <td>{{$player->lastseen}}</td>
                            </tr>
                            <tr>
                                <td>IP</td>
                                <td>{{$player->ip}}</td>
                            </tr>
                            <tr>
                                <td>Rank</td>
                                <td>{{$player->lastadminrank}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">User Whitelists</div>

                    <div class="panel-body">
                        <table class="table table-bordered">
                            <tbody>
                            @foreach($whitelists as $whitelist=>$status)
                                <tr>
                                    <td>{{$whitelist}}</td>
                                    @if($status == TRUE)
                                        <td>
                                            <span class="label label-success">
                                                <span class="glyphicon glyphicon-ok"></span>
                                            </span>
                                        </td>
                                        @can('admin_whitelists_edit')
                                        <td>
                                            <a href="{{route('admin.whitelist.remove',['player_id'=>$player->id,'whitelist'=>$whitelist])}}"
                                               class="btn btn-danger" role="button">Remove Whitelist</a>
                                        </td>
                                        @endcan
                                    @else
                                        <td>
                                            <span class="label label-danger">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </span>
                                        </td>
                                        @can('admin_whitelists_edit')
                                        <td>
                                            <a href="{{route('admin.whitelist.add',['player_id'=>$player->id,'whitelist'=>$whitelist])}}"
                                               class="btn btn-success" role="button">Add Whitelist</a>
                                        </td>
                                        @endcan
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
