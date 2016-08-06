<p>
    <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{ $comment->created_at->diffForHumans() }}  </small>
    <small class="text-muted"><i class="glyphicon glyphicon-user"></i> Made by: <b>{{$comment->commentor_name}}</b></small>
</p>