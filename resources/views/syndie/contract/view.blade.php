@extends('layouts.app')

@section('styles')
    <link href="{{asset('assets/css/timeline.css')}}" rel="stylesheet">
@endsection


@section('content')
    <div class="container">
        @include('components.formerrors')
        {{-- Contract Overview --}}
        <div class="row">
            {{-- Details about the contract--}}
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Contract Details</div>

                    <div class="panel-body">
                        <p><b>Title:</b> {{$contract->title}}</p>
                        <p><b>Contractee:</b> {{$contract->contractee_name}}</p>
                        <p><b>Status:</b> {{$contract->status}}</p>
                        <p><b>Reward:</b> {{$contract->reward_other}}</p>

                        <p><b>Description:</b></p>
                        <p>{{$contract->description}}</p>
                    </div>
                </div>
            </div>
            {{-- Management Panel--}}
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Actions</div>
                    <div class="panel-body">
                        {{-- Check if the user is the contractee --}}
                        @if(Auth::user()->user_id == $contract->contractee_id)
                        <p><b>Contractee Actions</b></p>
                        <p><a href="#" class="btn btn-success" role="button">Confirm Completion</a></p>
                        <p><a href="#" class="btn btn-danger" role="button">Cancel Contract</a></p>
                        @endif()
                        {{-- Check if user is a contract mod--}}
                        @can('contract_moderate')
                        <p><b>Mod Actions</b></p>
                        <p><a href="#" class="btn btn-info" role="button">Approve Contract</a></p>
                        <p><a href="#" class="btn btn-danger" role="button">Cancel Contract</a></p>
                        <p><a href="#" class="btn btn-success" role="button">Confirm Completion</a></p>
                        <p><a href="#" class="btn btn-info" role="button">Link Button</a></p>
                        @endcan('')
                    </div>
                </div>
            </div>
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
                                                <small class="text-muted"><i class="glyphicon glyphicon-time"></i> 11 hours ago  </small>
                                                <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>{{$comment->comment}}</p>
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
                                                <small class="text-muted"><i class="glyphicon glyphicon-time"></i> 11 hours ago  </small>
                                                <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>{{$comment->comment}}</p>
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
                                                <small class="text-muted"><i class="glyphicon glyphicon-time"></i> 11 hours ago  </small>
                                                <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>{{$comment->comment}}</p>
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
                                                <small class="text-muted"><i class="glyphicon glyphicon-time"></i> 11 hours ago  </small>
                                                <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>{{$comment->comment}}</p>
                                        </div>
                                    </div>
                                </li>
                            @elseif($comment->type === 'ooc')

                            @endif()
                        @endif()
                    @endforeach

                            {{--<li>--}}
                            {{--<div class="timeline-badge"><i class="glyphicon glyphicon-check"></i></div>--}}
                            {{--<div class="timeline-panel">--}}
                            {{--<div class="timeline-heading">--}}
                            {{--<h4 class="timeline-title">Mussum ipsum cacilds</h4>--}}
                            {{--<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> 11 hours ago via Twitter</small></p>--}}
                            {{--</div>--}}
                            {{--<div class="timeline-body">--}}
                            {{--<p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.</p>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</li>--}}
                            {{--<li class="timeline-inverted">--}}
                            {{--<div class="timeline-badge warning"><i class="glyphicon glyphicon-credit-card"></i></div>--}}
                            {{--<div class="timeline-panel">--}}
                            {{--<div class="timeline-heading">--}}
                            {{--<h4 class="timeline-title">Mussum ipsum cacilds</h4>--}}
                            {{--</div>--}}
                            {{--<div class="timeline-body">--}}
                            {{--<p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.</p>--}}
                            {{--<p>Suco de cevadiss, é um leite divinis, qui tem lupuliz, matis, aguis e fermentis. Interagi no mé, cursus quis, vehicula ac nisi. Aenean vel dui dui. Nullam leo erat, aliquet quis tempus a, posuere ut mi. Ut scelerisque neque et turpis posuere pulvinar pellentesque nibh ullamcorper. Pharetra in mattis molestie, volutpat elementum justo. Aenean ut ante turpis. Pellentesque laoreet mé vel lectus scelerisque interdum cursus velit auctor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac mauris lectus, non scelerisque augue. Aenean justo massa.</p>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</li>--}}
                </ul>
            </div>
        </div>
        {{-- New PM Panel--}}
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">New Message</div>

                    <div class="panel-body">
                        {{ Form::open(array('route' => array('syndie.contracts.addmessage.post',$contract->contract_id),'method' => 'post')) }}

                        {{Form::token()}}

                        {{--Only show the commentor name field of the user is not the owner of the contract or a mod--}}
                        @if(Auth::user()->user_id != $contract->contractee_id || Auth::user()->can('contract_moderate'))
                            {{Form::bsText('commentor_name')}}
                        @else
                            {{Form::hidden('commentor_name',$contract->contractee_name)}}
                        @endif()

                        @if(Auth::user()->user_id == $contract->contractee_id){{-- Check if user is contract owner --}}
                        {{Form::bsSelectList('type',array('ic'=>'IC Comment','ooc' => 'OOC Comment','mod-author'=>'MOD-Author PM'))}}
                        @elseif(Auth::user()->can('contract_moderate')){{-- Check if user is contract mod --}}
                        {{Form::bsSelectList('type',array('ic'=>'IC Comment','ic-failrep'=> 'IC Failure Report','ic-comprep'=>'IC Completion Report','ooc' => 'OOC Comment','mod-author'=>'MOD-Author PM','mod-ooc'=>'MOD-OOC Message'))}}
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
