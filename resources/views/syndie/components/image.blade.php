@if($comment->image_name != NULL)
    <p>
        <a href="{{asset('images/contractcomment/'.$comment->image_name)}}" data-toggle="lightbox" data-title="Comment Image: {{$comment->comment_id}}" data-footer="">
            <img src="{{asset('images/contractcomment/'.$comment->image_name)}}" class="img-responsive">
        </a>
    </p>
@endif()