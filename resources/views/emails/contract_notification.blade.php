Hello {{$forum_user->username}},<br>
<br>
Contract ID {{$contract->contract_id}} with the title {{$contract->title}} has been updated.<br>
<br>
Update type {{$type}}
<br>
You can view the update on the following page:<br>
<a href="{{route('syndie.contracts.show',['contract'=>$contract->contract_id])}}">{{route('syndie.contracts.show',['contract'=>$contract->contract_id])}}</a><br>
<br>
To unsubscribe from updates, visit the above page and click on unsubscribe.<br>
<br>
This is a automates message.<br>
Please do not reply to it<br>