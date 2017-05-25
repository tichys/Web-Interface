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
            <div class="col-md-10 col-md-offset-1">
                @include('components.formerrors')
                <div class="panel panel-default">
                    <div class="panel-heading">Add a new Comment</div>

                    <div class="panel-body">
                        {{Form::open(array('route' => ['server.git.todo.comment.post',$todo->todo_id],'method' => 'post')) }}
                        {{Form::token()}}
                        {{Form::bsSelectList('status',array(
                            'working'=>'Working',
                            'broken'=>'Broken'))}}
                        {{Form::bsText('description')}}

                        {{Form::submit('Submit', array('class'=>'btn btn-default'))}}

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$todo->description}}</div>

                    <div class="panel-body">
                        <table id="todo-comment-table" class="table table-condensed">
                            <thead>
                            <tr>
                                <th width="10px">#</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Comment</th>
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
            $('#todo-comment-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('server.git.todo.comment.data',["todo_id"=>$todo->todo_id]) }}'
            });
        });
    </script>
@endsection