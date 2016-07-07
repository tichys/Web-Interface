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
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Hair Colour/Style</div>

                <table class="table">
                    <tbody>
                    <tr>
                        <td><b>Hair Colour:</b></td>
                        <td bgcolor="{{$char->hair_colour}}"></td>
                    </tr>
                    <tr>
                        <td><b>Hair Style:</b></td>
                        <td>{{$char->hair_style}}</td>
                    </tr>
                    <tr>
                        <td><b>Facial Hair Colour:</b></td>
                        <td bgcolor="{{$char->facial_colour}}"></td>
                    </tr>
                    <tr>
                        <td><b>Facial Hair Style:</b></td>
                        <td>{{$char->facial_style}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Skin/Eye Color</div>

                <table class="table">
                    <tbody>
                    @if($char->species == "Human")
                    <tr>
                        <td><b>Skin Tone:</b></td>
                        <td>{{$char->skin_tone}}</td>
                    </tr>
                    @else()
                    <tr>
                        <td><b>Skin Colour:</b></td>
                        <td bgcolor="{{$char->skin_colour}}"></td>
                    </tr>
                    @endif()
                    <tr>
                        <td><b>Eye Colour:</b></td>
                        <td bgcolor="{{$char->eyes_colour}}"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Personal Data</div>

                <table class="table">
                    <tbody>
                    <tr>
                        <td><b>Home System:</b></td>
                        <td>{{$char->home_system}}</td>
                    </tr>
                    <tr>
                        <td><b>Citizenship:</b></td>
                        <td>{{$char->citizenship}}</td>
                    </tr>
                    <tr>
                        <td><b>Faction:</b></td>
                        <td>{{$char->faction}}</td>
                    </tr>
                    <tr>
                        <td><b>Religion:</b></td>
                        <td>{{$char->religion}}</td>
                    </tr>
                    <tr>
                        <td><b>NT-Relation:</b></td>
                        <td>{{$char->nt_relation}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Underwear / Undershirt / Backpack</div>

                <table class="table">
                    <tbody>
                    <tr>
                        <td><b>Underwear:</b></td>
                        <td>{{$char->underwear}}</td>
                    </tr>
                    <tr>
                        <td><b>Undershirt:</b></td>
                        <td>{{$char->undershirt}}</td>
                    </tr>
                    <tr>
                        <td><b>Backbag:</b></td>
                        <td>{{$char->backbag}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Employment Records</div>
                <div class="panel-body">@striptags($char_flavour->records_employment)</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Medical Records</div>
                <div class="panel-body">@striptags($char_flavour->records_medical)</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Security Records</div>
                <div class="panel-body">@striptags($char_flavour->records_security)</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">CCIA Records</div>
                <div class="panel-body">@striptags($char_flavour->records_ccia)</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Exploitable Information</div>
                <div class="panel-body">@striptags($char_flavour->records_exploit)</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p>
                @can('admin_char_show')<a href="{{route('server.chars.edit.cr.get',['char_id'=>$char->id])}}" class="btn btn-info" role="button">Edit CCIA Record</a>@endcan()
            </p>
        </div>
    </div>
</div>
@endsection