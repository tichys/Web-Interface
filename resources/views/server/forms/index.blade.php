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
    <link href="{{ asset('/DataTables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Form Overview</div>

                    <div class="panel-body">
                        @can('server_forms_edit')<p><a href="{{route('admin.forms.add.post')}}" class="btn btn-success" role="button">Add new Form</a></p>@endcan()
                        <table id="forms-table" class="table table-condensed">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Action</th>
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

    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script>
        $(function() {
            $('#forms-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.forms.data') }}',
                columns:[
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'department', name: 'department' },
                    { data: 'action', name: 'action' }
                ]
            });
        });
    </script>
@endsection