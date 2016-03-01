@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                @include('components.formerrors')

                <div class="panel panel-default">
                    <div class="panel-heading">New contract</div>


                    <div class="panel-body">
                        {{ Form::open(array('route' => 'syndie.contracts.add.post','method' => 'post')) }}

                        {{Form::token()}}

                        {{Form::bsText('contractee_name')}}
                        {{Form::bsText('title')}}
                        {{Form::bsTextArea('description')}}
                        {{Form::bsText('reward')}}

                        {{Form::submit('Submit', array('class'=>'btn btn-default'))}}

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
