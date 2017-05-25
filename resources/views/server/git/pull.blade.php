{{--Copyright (c) 2017 "Werner Maisl"--}}

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
                    <div class="panel-heading">Pull Request Details</div>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td>Internal ID</td>
                            <td>{{$pull->pull_id}}</td>
                        </tr>
                        <tr>
                            <td>Github ID</td>
                            <td>{{$pull->git_id}}</td>
                        </tr>
                        <tr>
                            <td>Title</td>
                            <td>{{$pull->title}}</td>
                        </tr>
                        <tr>
                            <td>Merged Into</td>
                            <td>{{$pull->merged_into}}</td>
                        </tr>
                        <tr>
                            <td>Stats</td>
                            <td>
                                <span class="label label-success">{{$pull->working}}</span>
                                <span class="label label-danger">{{$pull->broken}}</span>
                                <span class="label label-info">{{$pull->untested}}</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Pull Request Text</div>

                    <div class="panel-body"><pre>{{$pull->body}}</pre></div>

                </div>
            </div>
        </div>
        {{-- ToDos --}}
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Pull Request ToDos</div>

                    <div class="panel-body">
                        <table id="pull-comment-table" class="table table-condensed">
                            <thead>
                            <tr>
                                <th width="10px">#</th>
                                <th>Description</th>
                                <th>Working / Broken</th>
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
            $('#pull-comment-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('server.git.todo.data',["pull_id"=>$pull->pull_id]) }}'
            });
        });
    </script>
@endsection