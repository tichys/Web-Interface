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
            <div class="alert alert-success">This is the User Dashboard, here you can find various infos about your
                useraccount on the server
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Warning Status</div>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td>Total Warnings</td>
                            <td><span class="label label-default">{{$warnings->get_total_count()}}</span></td>
                        </tr>
                        <tr>
                            <td>Major Warnings</td>
                            <td><span class="label label-warning">{{$warnings->get_major_count()}}</span></td>
                        </tr>
                        <tr>
                            <td>Unacknowledged Warnings</td>
                            <td><span class="label label-danger">{{$warnings->get_unack_count()}}</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Whitelist Status</div>

                    <div class="panel-body">
                        @include('components.whiteliststatustable')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
