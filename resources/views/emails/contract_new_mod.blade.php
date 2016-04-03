Hello {{$forum_user->username}},<br>
<br>
Contract ID {{$contract->contract_id}} with the title {{$contract->title}} has been created.<br>
<br>
You can view the update on the following page:<br>
<a href="{{route('syndie.contracts.show',['contract'=>$contract->contract_id])}}">{{route('syndie.contracts.show',['contract'=>$contract->contract_id])}}</a><br>
<br>
Please review and approve or reject that contract
<br>
You receive this mail because you are a contract moderator