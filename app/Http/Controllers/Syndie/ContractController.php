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

class ContractController extends Controller
{
    public function index(Request $request)
    {
        //For contract mods: Show all contracts
        if ($request->user()->can('contract_moderate')) {
            $contracts = SyndieContract::all();
        } else //For normal users: Show all contracts that have a status of open, assigned, completed, confirmed or that they own
        {
            $contracts = SyndieContract::whereIn('status', ['open', 'assigned', 'completed', 'confirmed'])->OrWhere('contractee_id', '=', $request->user()->user_id)->get();
        }

        return view('syndie.contract.index', ['contracts' => $contracts]);
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

        $contract = new SyndieContract();
        $contract->contractee_id = $user->user_id;
        $contract->status = "new";
        $contract->reward_other = $request->input('reward');
        $contract->contractee_name = strip_tags($request->input('contractee_name'));
        $contract->title = strip_tags($request->input('title'));
        $contract->description = strip_tags($request->input('description'));
        $contract->save();

        //Add a info that the contract has been confirmed as completed.
        $SystemComment = new SyndieContractComment;
        $SystemComment->contract_id = $contract->contract_id;
        $SystemComment->commentor_id = 0;
        $SystemComment->commentor_name = "System";
        $SystemComment->title = "Contract Added";
        $SystemComment->comment = "The has been added to our database.
        A contract moderator will review this contract shortly.
        Thank you for your patience.";
        $SystemComment->type = 'ic';
        $SystemComment->save();

        return redirect()->route('syndie.contracts.index');
    }

    public function show($contract)
    {
        $contract = SyndieContract::find($contract);
        $comments = SyndieContractComment::where('contract_id', '=', $contract->contract_id)->get();

        return view('syndie.contract.view', ['contract' => $contract, 'comments' => $comments]);
    }

    public function getEdit(Request $request, $contract)
    {
        return "Edit: " . $contract;
    }

    public function approve(Request $request, $contract)
    {
        //Check if player is contract mod
        if ($request->user()->can('contract_moderate')) {
            $SyndieContract = SyndieContract::find($contract);
            $SyndieContract->status = "open";
            $SyndieContract->save();

            //Add a info that the contract has been confirmed as completed.
            $SystemComment = new SyndieContractComment;
            $SystemComment->contract_id = $SyndieContract->contract_id;
            $SystemComment->commentor_id = 0;
            $SystemComment->commentor_name = "System";
            $SystemComment->title = "Contract Approved";
            $SystemComment->comment = "The contract has been approved by a contract moderator.
            Contractors are now able to see it in the contract overview.";
            $SystemComment->type = 'ic';
            $SystemComment->save();

            return redirect()->route('syndie.contracts.show', ['contract' => $contract]);
        } else {
            abort(403, 'Unauthorized action.');
        }

    }

    public function reject(Request $request, $contract)
    {
        //Check if player is contract mod
        if ($request->user()->can('contract_moderate')) {
            $SyndieContract = SyndieContract::find($contract);
            $SyndieContract->status = "mod-nok";
            $SyndieContract->save();
            return redirect()->route('syndie.contracts.show', ['contract' => $contract]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function confirm(Request $request, $comment)
    {
        $SyndieComment = SyndieContractComment::find($comment);

        $SyndieContract = SyndieContract::find($SyndieComment->contract_id);
        //Check if contract is completed
        if($SyndieContract->status !== "completed")
        {
            return redirect()->route('syndie.contracts.show', ['contract' => $SyndieComment->contract_id])->withErrors(array('You can not confirm a contract as complented when no completion report has been posted'));
        }

        //Check if player is mod or contract owner
        if ($request->user()->can('contract_moderate') || $request->user()->user_id == $SyndieContract->contractee_id) {
            $SyndieContract->status = "closed";
            $SyndieContract->completer_id = $SyndieComment->commentor_id;
            $SyndieContract->completer_name = strip_tags($SyndieComment->commentor_name);
            $SyndieContract->save();
        } else {
            abort(403, 'Unauthorized action.');
        }

        //Add a info that the contract has been confirmed as completed.
        $SystemComment = new SyndieContractComment;
        $SystemComment->contract_id = $SyndieContract->contract_id;
        $SystemComment->commentor_id = 0;
        $SystemComment->commentor_name = "System";
        $SystemComment->title = "Contract Confirmed as Completed";
        $SystemComment->comment = "The contractee has confirmed that the contract has been completed by the contractor.
        The funds have been transfered to the contractor.
        Thank you for choosing our Contract Service.";
        $SystemComment->type = 'ic';
        $SystemComment->save();

        //Return the player to the page
        return redirect()->route('syndie.contracts.show', ['contract' => $SyndieComment->contract_id]);
    }

    public function reopen(Request $request, $comment)
    {
        $SyndieComment = SyndieContractComment::find($comment);
        $SyndieContract = SyndieContract::find($SyndieComment->contract_id);

        //Check if contract is completed
        if($SyndieContract->status !== "completed")
        {
            return redirect()->route('syndie.contracts.show', ['contract' => $SyndieComment->contract_id])->withErrors(array('You can not reopen a contract when no completion report has been posted'));
        }

        //Check if player is mod or contract owner
        if ($request->user()->can('contract_moderate') || $request->user()->user_id == $SyndieContract->contractee_id) {
            $contr = SyndieContract::find($SyndieComment->contract_id);
            $contr->status = "open";
            $contr->save();
            return redirect()->route('syndie.contracts.show', ['contract' => $SyndieComment->contract_id]);
        } else {
            abort(403, 'Unauthorized action.');
        }

        //Add a info that the contract has been confirmed as completed.
        $SystemComment = new SyndieContractComment;
        $SystemComment->contract_id = $SyndieContract->contract_id;
        $SystemComment->commentor_id = 0;
        $SystemComment->commentor_name = "System";
        $SystemComment->title = "Contract Reopened";
        $SystemComment->comment = "The contractee has rejected the completion report.
        The contract has been reopened.
        The contractee is expected to provide a explanation, why the completion report is not satisfying";
        $SystemComment->type = 'ic';
        $SystemComment->save();
    }

    public function cancel(Request $request, $contract)
    {

    }

    public function addMessage($contract, Request $request)
    {
        $SyndieContract = SyndieContract::find($contract);

        $this->validate($request, [
            'commentor_name' => 'required',
            'type' => 'required',
            'title' => 'required',
            'comment' => 'required'
        ]);

        $commentor_name = $request->input('commentor_name');
        $type = $request->input('type');
        $title = $request->input('title');
        $comment = $request->input('comment');

        //Check if the user is contractee, mod or just user and allow them to use the various pm types
        if ($request->user()->can('contract_moderate')) { //User is a contract moderator
            //User can use every message type and specify name. Just check that all fields are filled out
        } elseif ($request->user()->user_id == $SyndieContract->contractee_id) { //Use is contract author
            //User can not specify a name and can use only the following message types: 'ic'=>'IC Comment','ooc' => 'OOC Comment','mod-author'=>'MOD-Author PM'
            $commentor_name = $SyndieContract->contractee_name;

            $useable = array('ic', 'ic-cancel','ooc', 'mod-author');
            if (!in_array($type, $useable)) {
                return redirect()->route('syndie.contracts.show', ['contract' => $contract])->withErrors(array('You are not authorized to use this message type'));
            }
        } else { // User is not a contract mod or the author
            // user can only use the following message types: 'ic'=>'IC Comment','ic-failrep'=> 'IC Failure Report','ic-comprep'=>'IC Completion Report','ooc' => 'OOC Comment'
            $useable = array('ic', 'ic-failrep', 'ic-comprep', 'ooc');
            if (!in_array($type, $useable)) {
                return redirect()->route('syndie.contracts.show', ['contract' => $contract])->withErrors(array('You are not authorized to use this message type'));
            }
        }

        // Post the comment
        $SyndieContractComment = new SyndieContractComment();
        $SyndieContractComment->contract_id = $SyndieContract->contract_id;
        $SyndieContractComment->commentor_id = $request->user()->user_id;
        $SyndieContractComment->commentor_name = strip_tags($commentor_name);
        $SyndieContractComment->type = $type;
        $SyndieContractComment->title = strip_tags($title);
        $SyndieContractComment->comment = strip_tags($comment);
        $SyndieContractComment->save();

        //Check if the comment is a completion report -> if so update contract status to completed
        if ($type == 'ic-comprep') {
            $SyndieContract->status = "completed";
            $SyndieContract->save();
        }

        //Check if cancel message
        if ($type == 'ic-cancel') {
            $SyndieContract->status = "canceled";
            $SyndieContract->save();
        }

        return redirect()->route('syndie.contracts.show', ['contract' => $contract]);
    }
}
