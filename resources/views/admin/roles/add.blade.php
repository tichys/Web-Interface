@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('components.formerrors')
            <div class="panel panel-default">
                <div class="panel-heading">Add a new Role</div>

                <div class="panel-body">
                    {{Form::open(array('route' => 'admin.roles.add.post','method' => 'post')) }}
                    {{Form::token()}}

                    {{Form::bsText('name')}}
                    {{Form::bsText('label')}}
                    {{Form::bsText('description')}}

                    {{Form::submit('Submit', array('class'=>'btn btn-default'))}}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
