@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Contract Overview</div>

                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Contractee Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contracts as $contract)
                                    <tr>
                                        <td width="10px">{{$contract->contract_id}}</td>
                                        <td><a href="{{route('syndie.contracts.show',['contract'=>$contract->contract_id])}}"><b>{{$contract->title}}</b></a></td>
                                        <td><i>{{$contract->contractee_name}}</i></td>
                                        <td>@include("components.syndiecontractstatus")</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
