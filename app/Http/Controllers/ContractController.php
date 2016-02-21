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
        if($request->user()->can('contract_moderate'))
        {
            $contracts = SyndieContract::all();
        }
        else //For normal users: Show all contracts that have a status of open, assigned, completed, confirmed or that they own
        {
            $contracts = SyndieContract::whereIn('status',['open','assigned','completed','confirmed'])->OrWhere('contractee_id','=',$request->user()->user_id)->get();
        }

        return view('syndie.contract.index',['contracts' => $contracts]);
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
        $comments = SyndieContractComment::where('contract_id','=',$contract->contract_id)->get();

        return view('syndie.contract.view',['contract' => $contract,'comments'=>$comments]);
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


    public function postAddMessage($contract)
    {
        return redirect()->route('syndie.contracts.show.get',['contract'=>$contract]);
    }
}
