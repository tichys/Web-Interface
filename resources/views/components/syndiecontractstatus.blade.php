@if($contract->status == "new")
    <span class="label label-default">New</span>
@elseif($contract->status == "open")
    <span class="label label-primary">Open</span>
@elseif($contract->status == "mod-nok")
    <span class="label label-danger">Mod Rejected</span>
@elseif($contract->status == "completd")
    <span class="label label-info">Completed</span>
@elseif($contract->status == "closed")
    <span class="label label-success">Finished</span>
@elseif($contract->status == "canceled")
    <span class="label label-warning">Canceled by Contractee</span>
@else
    <span class="label label-default">{{$contract->status}}</span>
@endif