@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Roles</div>
                    <table class="table table-bordered">
                        <thead>
                        <td>ID</td>
                        <td>Label</td>
                        <td>Description</td>
                        <td>Actions</td>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{$role->id}}</td>
                                <td><a href="{{route('admin.roles.edit.get',["role_id" => $role->id])}}">{{$role->label}}</a></td>
                                <td>{{$role->description}}</td>
                                <td>
                                    <a class="btn btn-info" href="{{route('admin.roles.edit.get',["role_id" => $role->id])}}">Edit</a>
                                    <a class="btn btn-danger" href="{{route('admin.roles.delete',["role_id" => $role->id])}}">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        <tr><td><a class="btn btn-success" href="{{route('admin.roles.add.get')}}">Add a Role</a></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
