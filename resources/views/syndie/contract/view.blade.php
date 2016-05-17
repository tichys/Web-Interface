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
    <link href="{{asset('assets/css/ekko-lightbox.min.css')}}" rel="stylesheet">
@endsection

@section('javascripts')
    <script src="{{asset('assets/js/ekko-lightbox.min.js')}}"></script>
    <script>
        $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>
@endsection()


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
                                <td>@include("syndie.components.contractstatus")</td>
                            </tr>
                            @if(Auth::user()->user_id == $contract->contractee_id || Auth::user()->can('contract_moderate') )
                                <tr>
                                    <td><a href="{{route('syndie.contracts.edit.get',['contract'=>$contract->contract_id])}}" class="btn btn-info" role="button">Edit the Contract</a></td>
                                    <td></td>
                                </tr>
                            @endif
                            <tr>
                                @if(!$contract->is_subscribed(Auth::user()->user_id))
                                    <td><a href="{{route('syndie.contracts.subscribe',['contract'=>$contract->contract_id])}}" class="btn btn-success" role="button">Subscribe to Updates</a></td>
                                @else()
                                    <td><a href="{{route('syndie.contracts.unsubscribe',['contract'=>$contract->contract_id])}}" class="btn btn-warning" role="button">Unsubscribe from Updates</a></td>
                                @endif()
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
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
                        {{-- Check if user is a contract mod--}}
                        @can('contract_moderate')
                        <p><b>Mod Actions</b></p>
                        <p><a href="{{route('syndie.contracts.approve',['contract'=>$contract->contract_id])}}" class="btn btn-info @if(!in_array($contract->status,['new','mod-nok'])) disabled @endif" role="button">Approve Contract</a></p>
                        <p><a href="{{route('syndie.contracts.reject',['contract'=>$contract->contract_id])}}" class="btn btn-warning @if($contract->status != 'new') disabled @endif" role="button">Reject Contract</a></p>
                        <p><a href="{{route('syndie.contracts.deletecontract',['contract'=>$contract->contract_id])}}" class="btn btn-danger" role="button">Delete Contract</a></p>
                        @endcan('')
                    </div>
                </div>
            </div>
            @endif()
        </div>
    </div>
@endsection