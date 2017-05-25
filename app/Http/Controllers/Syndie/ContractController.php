<?php
/**
 * Copyright (c) 2016 "Werner Maisl"
 *
 * This file is part of Aurorastation-Wi
 * Aurorastation-Wi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace App\Http\Controllers\Syndie;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\SyndieContract;
use App\Models\SyndieContractComment;
use App\Models\SyndieContractObjective;
use App\Models\ServerPlayer;
use Yajra\Datatables\Datatables;
use App\Jobs\SendContractNotificationEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Services\Server\Helpers;
use Illuminate\Support\Facades\Log;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        return view('syndie.contract.index');
    }

    public function show($contract)
    {
        $contract = SyndieContract::findOrFail($contract);
        $comments = SyndieContractComment::where('contract_id', '=', $contract->contract_id)->get();
        $objectives = SyndieContractObjective::where('contract_id', '=', $contract->contract_id)->get();

        return view('syndie.contract.view', ['contract' => $contract, 'objectives' => $objectives, 'comments' => $comments]);
    }

    public function getContractData(Request $request)
    {
        $contracts = SyndieContract::select(['contract_id', 'title', 'contractee_name', 'status']);
        //For contract mods: Show all contracts
        if ($request->user()->can('syndie_contract_moderate')) {

        } else //For normal users: Show all contracts that have a status of open, assigned, completed, confirmed or that they own
        {
            $contracts->whereIn('status', ['open', 'assigned', 'completed', 'confirmed'])->OrWhere('contractee_id', '=', $request->user()->user_id)->get();
        }

        return Datatables::of($contracts)
            ->editColumn('title', '<b><a href="{{route(\'syndie.contracts.show\',[\'contract\'=>$contract_id])}}">{{$title}}</a></b>')
            ->rawColumns([1])
            ->make();
    }

    public function getAdd()
    {
        return view('syndie.contract.add');
    }

    public function postAdd(Request $request)
    {
        $user = \Auth::user();

        $this->validate($request, [
            'contractee_name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'reward' => 'required'
        ]);

        $SyndieContract = new SyndieContract();
        $SyndieContract->contractee_id = $user->user_id;
        $SyndieContract->status = "new";
        $SyndieContract->reward_other = $request->input('reward');
        $SyndieContract->contractee_name = strip_tags($request->input('contractee_name'));
        $SyndieContract->title = strip_tags($request->input('title'));
        $SyndieContract->description = strip_tags($request->input('description'));
        $SyndieContract->save();

        $SyndieContract->add_subscribers($SyndieContract->contractee_id);

        $this->dispatch(new SendContractNotificationEmail($SyndieContract, 'new'));

        Log::notice('perm.contracts.add - Contract has been added',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContract->contract_id]);

        return redirect()->route('syndie.contracts.index');
    }

    public function getEdit(Request $request, $contract)
    {
        $SyndieContract = SyndieContract::find($contract);

        //Check if the user is the contract owner or a moderator
        if ($request->user()->cannot('syndie_contract_moderate') && $request->user()->user_id != $SyndieContract->contractee_id) {
            abort(502,"You do not have the permission to edit the contract");
        }

        return view('syndie.contract.edit', ['contract' => $SyndieContract]);
    }

    public function postEdit(Request $request, $contract)
    {
        $SyndieContract = SyndieContract::find($contract);

        //Check if the user is the contract owner or a moderator
        if ($request->user()->cannot('syndie_contract_moderate') && $request->user()->user_id != $SyndieContract->contractee_id) {
            abort(502,"You do not have the permission to edit the contract");
        }

        $SyndieContract->title = $request->input('title');
        $SyndieContract->description = $request->input('description');
        $SyndieContract->reward_other = $request->input('reward');
        $SyndieContract->save();

        Log::notice('perm.contracts.edit - Contract has been edited',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContract->contract_id]);

        return redirect()->route('syndie.contracts.show',['contract'=>$SyndieContract->contract_id]);

    }

    public function approve(Request $request, $contract)
    {
        //Check if player is contract mod
        if ($request->user()->can('syndie_contract_moderate')) {
            $SyndieContract = SyndieContract::find($contract);
            $SyndieContract->status = "open";
            $SyndieContract->save();

            $this->dispatch(new SendContractNotificationEmail($SyndieContract, 'approve'));

            Log::notice('perm.contracts.approve - Contract has been approved',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContract->contract_id]);

            return redirect()->route('syndie.contracts.show', ['contract' => $contract]);
        } else {
            abort(403, 'Unauthorized action.');
        }

    }

    public function reject(Request $request, $contract)
    {
        //Check if player is contract mod
        if ($request->user()->can('syndie_contract_moderate')) {
            $SyndieContract = SyndieContract::find($contract);
            $SyndieContract->status = "mod-nok";
            $SyndieContract->save();

            $this->dispatch(new SendContractNotificationEmail($SyndieContract, 'reject'));

            Log::notice('perm.contracts.reject - Contract has been rejected',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContract->contract_id]);

            return redirect()->route('syndie.contracts.show', ['contract' => $contract]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function getAgentList(Request $request)
    {
        $helpers = new Helpers();

        $term = $request->input('term');

        $search_key = $helpers->sanitize_ckey($term);

        //Check for proper input length
        if(strlen($term) >= 3)
        {
            //Get corresponding ckeys from DB
            $players = ServerPlayer::where('ckey','like','%'.$search_key.'%')->lists('ckey','id');
            return json_encode($players);
        }
    }

//    public function confirm(Request $request, $comment)
//    {
//        $SyndieComment = SyndieContractComment::find($comment);
//
//        $SyndieContract = SyndieContract::find($SyndieComment->contract_id);
//        //Check if contract is completed
//        if ($SyndieContract->status !== "completed") {
//            return redirect()->route('syndie.contracts.show', ['contract' => $SyndieComment->contract_id])->withErrors(array('You can not confirm a contract as complented when no completion report has been posted'));
//        }
//
//        //Check if player is mod or contract owner
//        if ($request->user()->can('syndie_contract_moderate') || $request->user()->user_id == $SyndieContract->contractee_id) {
//            $SyndieContract->status = "closed";
//            $SyndieContract->completer_id = $SyndieComment->commentor_id;
//            $SyndieContract->completer_name = strip_tags($SyndieComment->commentor_name);
//            $SyndieContract->save();
//        } else {
//            abort(403, 'Unauthorized action.');
//        }
//
//        //Add a info that the contract has been confirmed as completed.
//        $SystemComment = new SyndieContractComment;
//        $SystemComment->contract_id = $SyndieContract->contract_id;
//        $SystemComment->commentor_id = 0;
//        $SystemComment->commentor_name = "System";
//        $SystemComment->title = "Contract Confirmed as Completed";
//        $SystemComment->comment =
//"The contractee has confirmed that the contract has been completed by the contractor.
//
//The funds have been transfered to the contractor.
//
//Thank you for choosing our Contract Service.";
//        $SystemComment->type = 'ic';
//        $SystemComment->save();
//
//        $this->dispatch(new SendContractNotificationEmail($SyndieContract, 'confirm'));
//
//        Log::notice('perm.contracts.confirm - Contract has been confirmed',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContract->contract_id]);
//
//        //Return the player to the page
//        return redirect()->route('syndie.contracts.show', ['contract' => $SyndieComment->contract_id]);
//    }

//    public function reopen(Request $request, $comment)
//    {
//        $SyndieComment = SyndieContractComment::find($comment);
//        $SyndieContract = SyndieContract::find($SyndieComment->contract_id);
//
//        //Check if contract is completed
//        if ($SyndieContract->status !== "completed") {
//            return redirect()->route('syndie.contracts.show', ['contract' => $SyndieComment->contract_id])->withErrors(array('You can not reopen a contract when no completion report has been posted'));
//        }
//
//        //Check if player is mod or contract owner
//        if ($request->user()->can('syndie_contract_moderate') || $request->user()->user_id == $SyndieContract->contractee_id) {
//            $contr = SyndieContract::find($SyndieComment->contract_id);
//            $contr->status = "open";
//            $contr->save();
//            return redirect()->route('syndie.contracts.show', ['contract' => $SyndieComment->contract_id]);
//        } else {
//            abort(403, 'Unauthorized action.');
//        }
//
//        //Add a info that the contract has been confirmed as completed.
//        $SystemComment = new SyndieContractComment;
//        $SystemComment->contract_id = $SyndieContract->contract_id;
//        $SystemComment->commentor_id = 0;
//        $SystemComment->commentor_name = "System";
//        $SystemComment->title = "Contract Reopened";
//        $SystemComment->comment =
//"The contractee has rejected the completion report.
//
//The contract has been reopened.
//
//The contractee is expected to provide a explanation, why the completion report is not satisfying";
//        $SystemComment->type = 'ic';
//        $SystemComment->save();
//
//        Log::notice('perm.contracts.reopen - Contract has been reopened',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContract->contract_id]);
//
//        $this->dispatch(new SendContractNotificationEmail($SyndieContract, 'reopen'));
//    }

//    public function cancel(Request $request, $contract)
//    {
//
//    }

//    public function addMessage($contract, Request $request)
//    {
//        $SyndieContract = SyndieContract::find($contract);
//
//        $this->validate($request, [
//            'commentor_name' => 'required',
//            'type' => 'required',
//            'title' => 'required',
//            'comment' => 'required',
//            'image' => 'image'
//        ]);
//
//        $commentor_name = $request->input('commentor_name');
//        $type = $request->input('type');
//        $title = $request->input('title');
//        $comment = $request->input('comment');
//
//        //Check if the user is contractee, mod or just user and allow them to use the various pm types
//        if ($request->user()->can('syndie_contract_moderate')) { //User is a contract moderator
//            //User can use every message type and specify name. Just check that all fields are filled out
//        } elseif ($request->user()->user_id == $SyndieContract->contractee_id) { //Use is contract author
//            //User can not specify a name and can use only the following message types: 'ic'=>'IC Comment','ooc' => 'OOC Comment','mod-author'=>'MOD-Author PM'
//            $commentor_name = $SyndieContract->contractee_name;
//
//            $useable = array('ic', 'ic-cancel', 'ooc', 'mod-author');
//            if (!in_array($type, $useable)) {
//                return redirect()->route('syndie.contracts.show', ['contract' => $contract])->withErrors(array('You are not authorized to use this message type'));
//            }
//        } else { // User is not a contract mod or the author
//            // user can only use the following message types: 'ic'=>'IC Comment','ic-failrep'=> 'IC Failure Report','ic-comprep'=>'IC Completion Report','ooc' => 'OOC Comment'
//            $useable = array('ic', 'ic-failrep', 'ic-comprep', 'ooc');
//            if (!in_array($type, $useable)) {
//                return redirect()->route('syndie.contracts.show', ['contract' => $contract])->withErrors(array('You are not authorized to use this message type'));
//            }
//        }
//
//        //Check if the message type is ooc or mod-author or mod-ooc, then set the author name to the users form username
//        if (in_array($type, array('ooc', 'mod-author', 'mod-ooc'))) {
//            $commentor_name = $request->user()->username;
//        }
//
//        // Post the comment
//        $SyndieContractComment = new SyndieContractComment();
//        $SyndieContractComment->contract_id = $SyndieContract->contract_id;
//        $SyndieContractComment->commentor_id = $request->user()->user_id;
//        $SyndieContractComment->commentor_name = strip_tags($commentor_name);
//        $SyndieContractComment->type = $type;
//        $SyndieContractComment->title = strip_tags($title);
//        $SyndieContractComment->comment = strip_tags($comment);
//        $SyndieContractComment->save();
//
//
//        //Check for files
//        if($request->hasFile('image'))
//        {
//            $file = $request->file('image');
//            //Check if file uploaded successfully
//            $extension = $file->getClientOriginalExtension();
//            $name = $SyndieContractComment->comment_id.'-'.time().'-'.rand(0,9999).'.'.$extension;
//            Storage::disk('contractimages')->put($name,  File::get($file));
//            $SyndieContractComment->image_name = $name;
//            $SyndieContractComment->save();
//        }
//
//        //Check if the comment is a completion report -> if so update contract status to completed
//        if ($type == 'ic-comprep') {
//            $SyndieContract->status = "completed";
//            $SyndieContract->save();
//        }
//
//        //Check if cancel message
//        if ($type == 'ic-cancel') {
//            $SyndieContract->status = "canceled";
//            $SyndieContract->save();
//        }
//
//        //Check if the posting user is subscribed to the contract. If not subscribe him
//        if(!$SyndieContract->is_subscribed($request->user()->user_id))
//        {
//            $SyndieContract->add_subscribers($request->user()->user_id);
//        }
//
//        $this->dispatch(new SendContractNotificationEmail($SyndieContract, $type));
//
//        Log::notice('perm.contracts.comment.add - Contract Comment has been added',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContract->contract_id, 'comment_id' => $SyndieContractComment->comment_id]);
//
//        return redirect()->route('syndie.contracts.show', ['contract' => $contract]);
//    }

//    public function deleteMessage($comment, Request $request)
//    {
//        if($request->user()->cannot('syndie_contract_moderate'))
//        {
//            abort('403','You do not have the required permission');
//        }
//
//        $SyndieContractComment = SyndieContractComment::findOrfail($comment);
//        $contract = $SyndieContractComment->contract_id;
//
//        Log::notice('perm.contracts.comment.delete - Contract Comment has been deleted',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContractComment->contract_id, 'comment_id' => $SyndieContractComment->comment_id]);
//
//        $SyndieContractComment->delete();
//
//        return redirect()->route('syndie.contracts.show', ['contract' => $contract]);
//    }

    public function delete($contract , Request $request)
    {
        if($request->user()->cannot('syndie_contract_moderate'))
        {
            abort('403','You do not have the required permission');
        }
        $SyndieContract = SyndieContract::findOrfail($contract);
        $SyndieContract->delete();

        Log::notice('perm.contracts.delete - Contract has been deleted',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContract->contract_id]);

        return redirect()->route('syndie.contracts.index');
    }

    public function subscribe($contract, Request $request)
    {
        $SyndieContract = SyndieContract::findOrfail($contract);
        $SyndieContract->add_subscribers($request->user()->user_id);

        Log::notice('perm.contracts.subscribe - User Subscribed to Contract',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContract->contract_id]);

        return redirect()->route('syndie.contracts.show', ['contract' => $contract]);
    }

    public function unsubscribe($contract, Request $request)
    {
        $SyndieContract = SyndieContract::findOrfail($contract);
        $SyndieContract->remove_subscribers($request->user()->user_id);

        Log::notice('perm.contracts.unsubscribe - User Unsubscribed from Contract',['user_id' => $request->user()->user_id, 'contract_id' => $SyndieContract->contract_id]);

        return redirect()->route('syndie.contracts.show', ['contract' => $contract]);
    }
}
