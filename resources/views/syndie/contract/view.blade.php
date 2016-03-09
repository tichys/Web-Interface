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
    <link href="{{asset('assets/css/timeline.css')}}" rel="stylesheet">
@endsection


@section('content')
    <div class="container">
        @include('components.formerrors')

        {{--Errors and Warnings--}}
        @if($contract->status == 'new' )
            <div class="alert alert-warning">
                <strong>New Contract: </strong> This contract has not been approved by a contract mod. It is only visible to you and moderators
            </div>
        @endif
        @if($contract->status == 'mod-nok' )
            <div class="alert alert-danger">
                <strong>Rejected by Moderator: </strong> This contract has been rejected by a moderator. Check the comment why this happend and then improve the contract.
            </div>
        @endif
        @if($contract->status == 'completed')
            <div class="alert alert-success">
                <strong>Contract Completed: </strong> This contract has been marked as completed by a contractor. As Author, please confirm the completion or reopen the contract
            </div>
        @endif

        {{-- Contract Overview --}}
        <div class="row">
            {{-- Details about the contract--}}
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4><b>{{$contract->title}}</b></h4></div>

                    <table class="table">
                        <tbody>
                        <tr>
                            <td><b>Contractee:</b></td>
                            <td>{{$contract->contractee_name}}</td>
                        </tr>
                        <tr>
                            <td><b>Status:</b></td>
                            <td>@include("components.syndiecontractstatus")</td>
                        </tr>
                        <tr>
                            <td><b>Reward:</b></td>
                            <td>{{$contract->reward_other}}</td>
                        </tr>
                        </tbody>
                    </table>
                    {{--<div class="panel-body">--}}
                        {{--<p><b>Contractee:</b> <i>{{$contract->contractee_name}}</i></p>--}}
                        {{--<p><b>Status:</b> @include("components.syndiecontractstatus")</p>--}}
                        {{--<p><b>Reward:</b> {{$contract->reward_other}}</p>--}}
                    {{--</div>--}}
                </div>
            </div>
            @if(Auth::user()->cannot('contract_moderate'))<div class="col-lg-8">@else() <div class="col-lg-6"> @endif()
                <div class="panel panel-default">
                    <div class="panel-heading">Contract Description:</div>

                    <div class="panel-body">
                        <p>@parsedown($contract->description)</p>
                    </div>
                </div>
            </div>
            {{-- Management Panel--}}
            @if(Auth::user()->can('contract_moderate'))
            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Actions</div>
                    <div class="panel-body">
                        {{-- Check if the user is the contractee --}}
                        @if(Auth::user()->user_id == $contract->contractee_id && Auth::user()->cannot('contract_moderate'))
                        <p><b>Contractee Actions</b></p>
                        <p><a href="{{route('syndie.contracts.cancel',['contract'=>$contract->contract_id])}}" class="btn btn-danger" role="button">Cancel Contract</a></p>
                        @endif()
                        {{-- Check if user is a contract mod--}}
                        @can('contract_moderate')
                        <p><b>Mod Actions</b></p>
                        <p><a href="{{route('syndie.contracts.approve',['contract'=>$contract->contract_id])}}" class="btn btn-info @if(!in_array($contract->status,['new','mod-nok'])) disabled @endif" role="button">Approve Contract</a></p>
                        <p><a href="{{route('syndie.contracts.reject',['contract'=>$contract->contract_id])}}" class="btn btn-warning @if($contract->status != 'new') disabled @endif" role="button">Reject Contract</a></p>
                        @endcan('')
                    </div>
                </div>
            </div>
            @endif()
        </div>

        {{-- Message Timeline--}}
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1 id="timeline">Comments</h1>
                </div>
                <ul class="timeline">
                    @foreach($comments as $comment)
                        {{-- Check if the comment is a mod author comment and if the player is a mod or the author--}}
                        @if($comment->type !== 'mod-author' || Auth::user()->user_id == $contract->contractee_id || Auth::user()->can('contract_moderate') )

                            {{-- If Comment is mod-ooc -> Left side + ooc colors --}}
                            @if($comment->type === 'mod-ooc')
                                <li>
                                    <div class="timeline-badge danger"><i class="glyphicon glyphicon-warning-sign"></i></div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title"><b>Mod OOC: </b>{{$comment->title}}</h4>
                                            <p>
                                                <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{ $comment->created_at->diffForHumans() }}  </small>
                                                <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>{!! nl2br(e($comment->comment)) !!}</p>
                                        </div>
                                    </div>
                                </li>
                            @elseif($comment->type === 'mod-author')
                                <li>
                                    <div class="timeline-badge warning"><i class="glyphicon glyphicon-warning-sign"></i></div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title"><b>Private: </b>{{$comment->title}}</h4>
                                            <p>
                                                <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{ $comment->created_at->diffForHumans() }}  </small>
                                                <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>{!! nl2br(e($comment->comment)) !!}</p>
                                        </div>
                                    </div>
                                </li>
                            @elseif($comment->type === 'ic')
                                @if($comment->commentor_id == $contract->contractee_id)<li> @else <li class="timeline-inverted">@endif
                                    <div class="timeline-badge success"><i class="glyphicon glyphicon-envelope"></i></div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title"><b>Message: </b>{{$comment->title}}</h4>
                                            <p>
                                                <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{ $comment->created_at->diffForHumans() }}  </small>
                                                <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>{!! nl2br(e($comment->comment)) !!}</p>
                                        </div>
                                    </div>
                                </li>
                            @elseif($comment->type === 'ic-comprep')
                                <li class="timeline-inverted">
                                    <div class="timeline-badge success"><i class="glyphicon glyphicon-ok"></i></div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title"><b>Completion Report: </b>{{$comment->title}}</h4>
                                            <p>
                                                <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{ $comment->created_at->diffForHumans() }}  </small>
                                                <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>{!! nl2br(e($comment->comment)) !!}</p>
                                            @if($contract->status == "completed" && (Auth::user()->user_id == $contract->contractee_id || Auth::user()->can('contract_moderate')))
                                            <p>
                                                <a href="{{route('syndie.contracts.confirm',['comment'=>$comment->comment_id])}}" class="btn btn-success" role="button">Confirm Completion</a>
                                                <a href="{{route('syndie.contracts.reopen',['comment'=>$comment->comment_id])}}" class="btn btn-info" role="button">Reopen Contract</a>
                                            </p>
                                            @endif()
                                        </div>
                                    </div>
                                </li>
                                @elseif($comment->type === 'ic-failrep')
                                    <li class="timeline-inverted">
                                        <div class="timeline-badge danger"><i class="glyphicon glyphicon-remove"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title"><b>Failure Report: </b>{{$comment->title}}</h4>
                                                <p>
                                                    <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{ $comment->created_at->diffForHumans() }}  </small>
                                                    <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                                </p>
                                            </div>
                                            <div class="timeline-body">
                                                <p>{!! nl2br(e($comment->comment)) !!}</p>
                                            </div>
                                        </div>
                                    </li>
                                @elseif($comment->type === 'ic-cancel')
                                    <li>
                                        <div class="timeline-badge danger"><i class="glyphicon glyphicon-minus"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title"><b>Contract Canceled: </b>{{$comment->title}}</h4>
                                                <p>
                                                    <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{ $comment->created_at->diffForHumans() }}  </small>
                                                    <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                                </p>
                                            </div>
                                            <div class="timeline-body">
                                                <p>{!! nl2br(e($comment->comment)) !!}</p>
                                            </div>
                                        </div>
                                    </li>
                                @elseif($comment->type === 'ooc')
                                    @if($comment->commentor_id == $contract->contractee_id)<li> @else <li class="timeline-inverted">@endif
                                    <div class="timeline-badge"><i class="glyphicon"></i></div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title"><b>OOC Message: </b>{{$comment->title}}</h4>
                                            <p>
                                                <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{ $comment->created_at->diffForHumans() }}  </small>
                                                <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>{!! nl2br(e($comment->comment)) !!}</p>
                                        </div>
                                    </div>
                                </li>
                            @endif()
                        @endif()
                    @endforeach
                </ul>
            </div>
        </div>
        {{-- New PM Panel--}}
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">New Message</div>

                    <div class="panel-body">
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            You can use Markdown to in the comment field
                        </div>

                        {{ Form::open(array('route' => array('syndie.contracts.addmessage',$contract->contract_id),'method' => 'post')) }}

                        {{Form::token()}}

                        {{--Only show the commentor name field of the user is not the owner of the contract or a mod--}}
                        @if(Auth::user()->user_id != $contract->contractee_id || Auth::user()->can('contract_moderate'))
                            {{Form::bsText('commentor_name')}}
                        @else
                            {{Form::hidden('commentor_name',$contract->contractee_name)}}
                        @endif()

                        @if(Auth::user()->can('contract_moderate')){{-- Check if user is contract mod --}}
                        {{Form::bsSelectList('type',array('ic'=>'IC Comment','ic-failrep'=> 'IC Failure Report','ic-comprep'=>'IC Completion Report','ic-cancel'=>'IC Cancel Contract','ooc' => 'OOC Comment','mod-author'=>'MOD-Author PM','mod-ooc'=>'MOD-OOC Message'))}}
                        @elseif(Auth::user()->user_id == $contract->contractee_id ){{-- Check if user is contract owner --}}
                        {{Form::bsSelectList('type',array('ic'=>'IC Comment','ic-cancel'=>'IC Cancel Contract','ooc' => 'OOC Comment','mod-author'=>'MOD-Author PM'))}}
                        @else(){{-- Otherwise --}}
                        {{Form::bsSelectList('type',array('ic'=>'IC Comment','ic-failrep'=> 'IC Failure Report','ic-comprep'=>'IC Completion Report','ooc' => 'OOC Comment'))}}
                        @endif()
                        {{Form::bsText('title')}}
                        {{Form::bsTextArea('comment')}}

                        {{Form::submit('Submit', array('class'=>'btn btn-default'))}}

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection