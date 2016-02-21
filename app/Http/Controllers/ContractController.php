<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\SyndieContract;
use App\Models\SyndieContractComment;
use App\Models\SyndieContractImage;

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

        $contract = new SyndieContract($request->all());
        $contract->contractee_id = $user->user_id;
        $contract->status = "new";
        $contract->reward_other = $request->input('reward');
        $contract->save();
        return redirect()->route('syndie.contracts.index');
    }

    public function getShow($contract)
    {
        $contract = SyndieContract::find($contract);
        $comments = SyndieContractComment::where('contract_id', '=', $contract->contract_id)->get();

        return view('syndie.contract.view', ['contract' => $contract, 'comments' => $comments]);
    }

    public function getEdit($contract)
    {
        return "Edit: " . $contract;
    }

    public function getAccept($contract)
    {
        return "Accept: " . $contract;
    }

    public function getComplete($contract)
    {
        return "Complete: " . $contract;
    }

    public function getConfirm($contract)
    {
        return "Confirm: " . $contract;
    }


    public function postAddMessage($contract, Request $request)
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
        if ($request->user()->user_id == $SyndieContract->contractee_id) { //User can not specify a name and can use the following message types: 'ic'=>'IC Comment','ooc' => 'OOC Comment','mod-author'=>'MOD-Author PM'
            $commentor_name = $SyndieContract->contractee_name;

            $useable = array('ic','ooc','mod-author');
            if(!in_array($type,$useable))
            {
                return redirect()->route('syndie.contracts.show.get', ['contract' => $contract])->withErrors(array('You are not authorized to use this message type'));
            }
        } elseif ($request->user()->can('contract_moderate')) { //User can use every message type and specify name. Just check that all fields are filled out

        } else { // user can only use the following message types: 'ic'=>'IC Comment','ic-failrep'=> 'IC Failure Report','ic-comprep'=>'IC Completion Report','ooc' => 'OOC Comment'
            $useable = array('ic','ic-failrep','ic-comprep','ooc');
            if(!in_array($type,$useable))
            {
                return redirect()->route('syndie.contracts.show.get', ['contract' => $contract])->withErrors(array('You are not authorized to use this message type'));
            }
        }

        $SyndieContractComment = new SyndieContractComment();
        $SyndieContractComment->contract_id = $SyndieContract->contract_id;
        $SyndieContractComment->commentor_id = $request->user()->user_id;
        $SyndieContractComment->commentor_name = $commentor_name;
        $SyndieContractComment->type = $type;
        $SyndieContractComment->title = $title;
        $SyndieContractComment->comment = $comment;
        $SyndieContractComment->save();

        return redirect()->route('syndie.contracts.show.get', ['contract' => $contract]);
    }
}
