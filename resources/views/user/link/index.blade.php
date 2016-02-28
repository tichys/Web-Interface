@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Link Byond Account</div>

                <div class="panel-body">
                    {{-- Check if Byond Account is linked already, If so, then only show this message --}}
                    @if(Auth::user()->byond_linked == true)
                        <div class="alert alert-success">
                            <strong>Linking completed!</strong><br>
                            You have already linked your byond account with your forum account.
                            You should have additional menu items available in the user menu.
                        </div>
                    @else
                        {{-- Check if a linking is in progress, If so, then show info panel --}}
                        <div class="alert alert-warning">
                            <strong>Linking in Progress!</strong><br>
                            You have already initiated a linking request.
                            The next time you join the server, you should see a linking request.
                        </div>
                        {{ Form::open(array('route' => 'user.link.cancel','method' => 'post')) }}
                        {{Form::token()}}
                        {{Form::submit('Cancel Linking Request', array('class'=>'btn btn-default'))}}
                        {{ Form::close() }}

                        {{-- If not show the form --}}
                        <div class="alert alert-danger">
                            <strong>Byond Account not linked!</strong><br>
                            You have not linked you byond account to your forum account.<br>
                            To use more advanced features of the WebInterface, you have to link your byond account to your forum user account.
                        </div>
                        {{ Form::open(array('route' => 'user.link.add','method' => 'post')) }}
                        {{Form::token()}}
                        {{Form::bsText('Byond Username')}}
                        {{Form::submit('Submit', array('class'=>'btn btn-default'))}}
                        {{ Form::close() }}
                    @endif()
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
