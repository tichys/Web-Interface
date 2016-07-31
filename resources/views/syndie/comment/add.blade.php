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
            <div class="col-md-10 col-md-offset-1">

                @include('components.formerrors')

                <div class="panel panel-default">
                    <div class="panel-heading">New Comment</div>


                    <div class="panel-body">
                        {{ Form::open(array('route' => array('syndie.contracts.add.post',$contract->contract_id),'method' => 'post', 'files' => true)) }}

                        {{Form::token()}}

                        @if(Auth::user()->can('contract_moderate')){{-- Check if user is contract mod --}}
                        {{Form::bsSelectList('type',array('ic'=>'IC Comment','ic-failrep'=> 'IC Failure Report','ic-comprep'=>'IC Completion Report','ic-cancel'=>'IC Cancel Contract','ooc' => 'OOC Comment','mod-author'=>'MOD-Author PM','mod-ooc'=>'MOD-OOC Message'))}}
                        @elseif(Auth::user()->user_id == $contract->contractee_id ){{-- Check if user is contract owner --}}
                        {{Form::bsSelectList('type',array('ic'=>'IC Comment','ic-cancel'=>'IC Cancel Contract','ooc' => 'OOC Comment','mod-author'=>'MOD-Author PM'))}}
                        @else(){{-- Otherwise --}}
                        {{Form::bsSelectList('type',array('ic'=>'IC Comment','ic-failrep'=> 'IC Failure Report','ic-comprep'=>'IC Completion Report','ooc' => 'OOC Comment'))}}
                        @endif()

                        {{--Only show the commentor name field of the user is not the owner of the contract or a mod--}}
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            The username is forced to your forum username for the following message types: 'ooc','mod-author','mod-ooc'
                        </div>
                        {{Form::bsText('commentor_name')}}

                        {{Form::bsText('title')}}

                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            You can use Markdown in the comment field
                        </div>
                        {{Form::bsTextArea('comment')}}

                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            Uploading a image is optional. You can only upload one image at a time.<br>
                            It is recommended to provide a description for each image you upload and then post a contract report.
                        </div>

                        <div id="objectives" hidden>
                            Test div for objectives
                        </div>
                        <div id="agents" hidden>
                            Test div for agents
                        </div>
                        {{Form::bsFile('image')}}

                        {{Form::submit('Submit', array('class'=>'btn btn-default'))}}

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script>
        $("#type").change(function () {

            $("#title").hide();
            $('label[for="title"]').hide();
            $("#comment").hide();
            $('label[for="comment"]').hide();
            $("#commentor_name").hide();
            $('label[for="commentor_name"]').hide();
            $("#image").hide();
            $('label[for="image"]').hide();
            $("#objectives").hide();
            $('label[for="objectives"]').hide();
            $("#agents").hide();
            $('label[for="agents"]').hide();

            var type = $( "#type option:selected" ).val();
            if(type == "ic")
            {
                $("#title").show();
                $('label[for="title"]').show();

                $("#comment").show();
                $('label[for="comment"]').show();

                $("#commentor_name").show();
                $('label[for="commentor_name"]').show();

                $("#image").show();
                $('label[for="image"]').show();
            }
            else if(type == "ic-failrep")
            {
                $("#title").show();
                $('label[for="title"]').show();

                $("#comment").show();
                $('label[for="comment"]').show();

                $("#commentor_name").show();
                $('label[for="commentor_name"]').show();

                $("#image").show();
                $('label[for="image"]').show();
            }
            else if(type == "ic-comprep")
            {
                $("#title").show();
                $('label[for="title"]').show();

                $("#comment").show();
                $('label[for="comment"]').show();

                $("#commentor_name").show();
                $('label[for="commentor_name"]').show();

                $("#image").show();
                $('label[for="image"]').show();

                $("#objectives").show();
                $('label[for="objectives"]').show();

                $("#agents").show();
                $('label[for="agents"]').show();

            }
            else if(type == "ic-cancel")
            {
                $("#title").show();
                $('label[for="title"]').show();

                $("#comment").show();
                $('label[for="comment"]').show();

                $("#commentor_name").show();
                $('label[for="commentor_name"]').show();

                $("#image").show();
                $('label[for="image"]').show();
            }
            else if(type == "ooc")
            {
                $("#title").show();
                $('label[for="title"]').show();

                $("#comment").show();
                $('label[for="comment"]').show();
            }
            else if(type == "mod-author")
            {
                $("#title").show();
                $('label[for="title"]').show();

                $("#comment").show();
                $('label[for="comment"]').show();
            }
            else if(type == "mod-ooc")
            {
                $("#title").show();
                $('label[for="title"]').show();

                $("#comment").show();
                $('label[for="comment"]').show();
            }

//            alert(type);
        });
    </script>
@endsection