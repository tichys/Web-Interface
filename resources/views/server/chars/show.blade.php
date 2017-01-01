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
                    @can('server_chars_show')
                        <tr>
                            <td><b>Owner ckey:</b></td>

                            <td>
                                @can('server_players_show')<a href="{{route('server.players.ckey',["ckey"=>$char->ckey])}}">@endcan()
                                    {{$char->ckey}}
                                @can('server_players_show')</a>@endcan()
                            </td>
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
        <div class="cold-md-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#recemploy">Employment</a></li>
                <li><a data-toggle="tab" href="#recmed">Medical</a></li>
                <li><a data-toggle="tab" href="#recsec">Security</a></li>
                <li><a data-toggle="tab" href="#recccia">CCIA</a></li>
                <li><a data-toggle="tab" href="#recexploit">Exploit</a></li>
            </ul>

            <div class="tab-content">
                <div id="recemploy" class="tab-pane fade in active">
                    <div class="panel panel-default">
                        <div class="panel-heading">Employment Records</div>
                        <div class="panel-body">@striptags($char_flavour->records_employment)</div>
                    </div>
                </div>
                <div id="recmed" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Medical Records</div>
                        <div class="panel-body">@striptags($char_flavour->records_medical)</div>
                    </div>
                </div>
                <div id="recsec" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Security Records</div>
                        <div class="panel-body">@striptags($char_flavour->records_security)</div>
                    </div>
                </div>
                <div id="recccia" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">CCIA Records</div>
                        <div class="panel-body">@striptags($char_flavour->records_ccia)
                            <p>
                                @can('server_chars_show')<a href="{{route('server.chars.edit.cr.get',['char_id'=>$char->id])}}" class="btn btn-info" role="button">Edit CCIA Record</a>@endcan()
                            </p>
                        </div>
                    </div>
                </div>
                <div id="recexploit" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Exploitable Information</div>
                        <div class="panel-body">@striptags($char_flavour->records_exploit)</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="cold-md-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#flavgen">General</a></li>
                <li><a data-toggle="tab" href="#flavhead">Head</a></li>
                <li><a data-toggle="tab" href="#flavface">Face</a></li>
                <li><a data-toggle="tab" href="#flaveyes">Eyes</a></li>
                <li><a data-toggle="tab" href="#flavtorso">Torso</a></li>
                <li><a data-toggle="tab" href="#flavarms">Arms</a></li>
                <li><a data-toggle="tab" href="#flavhands">Hands</a></li>
                <li><a data-toggle="tab" href="#flavlegs">Legs</a></li>
                <li><a data-toggle="tab" href="#flavfeet">Feet</a></li>
            </ul>

            <div class="tab-content">
                <div id="flavgen" class="tab-pane fade in active">
                    <div class="panel panel-default">
                        <div class="panel-heading">Flavourtext - General</div>
                        <div class="panel-body">@striptags($char_flavour->flavour_general)</div>
                    </div>
                </div>
                <div id="flavhead" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Flavourtext - Head</div>
                        <div class="panel-body">@striptags($char_flavour->flavour_head)</div>
                    </div>
                </div>
                <div id="flavface" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Flavourtext - Face</div>
                        <div class="panel-body">@striptags($char_flavour->flavour_face)</div>
                    </div>
                </div>
                <div id="flaveyes" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Flavourtext - Eyes</div>
                        <div class="panel-body">@striptags($char_flavour->flavour_eyes)</div>
                    </div>
                </div>
                <div id="flavtorso" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Flavourtext - Torso</div>
                        <div class="panel-body">@striptags($char_flavour->flavour_torso)</div>
                    </div>
                </div>
                <div id="flavarms" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Flavourtext - Arms</div>
                        <div class="panel-body">@striptags($char_flavour->flavour_arms)</div>
                    </div>
                </div>
                <div id="flavhands" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Flavourtext - Hands</div>
                        <div class="panel-body">@striptags($char_flavour->flavour_hands)</div>
                    </div>
                </div>
                <div id="flavlegs" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Flavourtext - Legs</div>
                        <div class="panel-body">@striptags($char_flavour->flavour_legs)</div>
                    </div>
                </div>
                <div id="flavfeet" class="tab-pane fade in">
                    <div class="panel panel-default">
                        <div class="panel-heading">Flavourtext - Feet</div>
                        <div class="panel-body">@striptags($char_flavour->flavour_feet)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Incident Overview</div>

                <div class="panel-body">
                    <table id="incidents-table" class="table table-condensed">
                        <thead>
                        <tr>
                            <th width="20px">ID</th>
                            <th width="20px">DateTime</th>
                            <th>Notes</th>
                            <th width="50px">Brig Sentence</th>
                            <th width="50px">Fine</th>
                            <th width="20px">Status</th>
                            <th width="20px">Actions</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascripts')
    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/datatables.bootstrap.js') }}"></script>
    <script>
        $(function() {
            $('#incidents-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('server.incidents.data.char',["char_id" => $char->id]) }}'
            });
        });
    </script>
@endsection