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
            @include('components.formerrors')
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4><b>{{$char->name}}</b></h4></div>

                    <table class="table">
                        <tbody>
                        <tr>
                            <td><b>Species:</b></td>
                            <td>{{$char->species}}</td>
                        </tr>
                        <tr>
                            <td><b>Gender:</b></td>
                            <td>{{$char->gender}}</td>
                        </tr>
                        <tr>
                            <td><b>Age:</b></td>
                            <td>{{$char->age}}</td>
                        </tr>
                        <tr>
                            <td><b>Blood Type:</b></td>
                            <td>{{$char->b_type}}</td>
                        </tr>
                        @can('admin_char_show')
                            <tr>
                                <td><b>Owner ckey:</b></td>
                                <td>{{$char->ckey}}</td>
                            </tr>
                        @endcan()
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">CCIA Records</div>
                    <div class="panel-body">
                        {{Form::model($char_flavour, array('route' => array('server.chars.edit.cr.post', $char_flavour->char_id),'method' => 'post')) }}
                        {{Form::token()}}
                        {{Form::bsTextArea('records_ccia')}}
                        @can('ccia_record_edit'){{Form::submit('Submit', array('class'=>'btn btn-default'))}}@endcan()
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection