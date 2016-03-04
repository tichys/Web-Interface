@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @foreach($warnings as $warning)
                <div class="panel panel-default @if($warning->severity == 1)panel-danger @else panel-warning @endif">
                    <div class="panel-heading">Warning received by <b>{{$warning->a_ckey}}</b>  {{$warning->diff}}</div>

                    <div class="panel-body">
                        {{$warning->reason}}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
