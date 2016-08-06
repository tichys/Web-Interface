<?php

namespace App\Http\Controllers\Syndie;

use App\Models\SyndieContractComment;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SyndieContract;
use App\Models\SyndieContractObjective;

class ContractObjective extends Controller
{
    public function view(Request $request, $objective)
    {
        $objective = SyndieContractObjective::findOrFail($objective);
        $contract = SyndieContract::findOrFail($objective->contract_id);
        return view('syndie.objective.view', ['objective' => $objective, 'contract' => $contract]);
    }

    public function getAdd(Request $request, $contract)
    {
        $contract = SyndieContract::findOrFail($contract);
        if($request->user()->cannot('contract_moderate') && $contract->contractee_id != $request->user()->id)
        {
            abort('403','You do not have the required permission');
        }

        return view('syndie.objective.add', ['contract' => $contract]);
    }

    public function postAdd(Request $request, $contract)
    {
        $contract = SyndieContract::findOrFail($contract);
        if($request->user()->cannot('contract_moderate') && $contract->contractee_id != $request->user()->id)
        {
            abort('403','You do not have the required permission');
        }

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'reward' => 'required'
        ]);

        $objective = new SyndieContractObjective();
        $objective->contract_id = $contract;
        $objective->status = "open";
        $objective->title = $request->input('title');
        $objective->description = $request->input('description');
        //$objective->reward_credits = $request->input('reward');
        $objective->reward_other = $request->input('reward');
        $objective->save();

        return redirect()->route('syndie.contracts.show',['contract'=>$objective->contract_id]);
    }

    public function getEdit(Request $request, $objective)
    {
        $objective = SyndieContractObjective::findOrFail($objective);
        $contract = $objective->contract()->get();
        if($request->user()->cannot('contract_moderate') && $contract->contractee_id != $request->user()->id)
        {
            abort('403','You do not have the required permission');
        }

        return view('syndie.objective.edit', ['objective' => $objective]);
    }

    public function postEdit(Request $request, $objective)
    {
        $objective = SyndieContractObjective::findOrFail($objective);
        $contract = $objective->contract()->get();
        if($request->user()->cannot('contract_moderate') && $contract->contractee_id != $request->user()->id)
        {
            abort('403','You do not have the required permission');
        }

        $objective->title = $request->input('title');
        $objective->description = $request->input('description');
        $objective->reward_credits = $request->input('reward_credits');
        $objective->reward_other = $request->input('reward_other');
        $objective->save();

        return redirect()->route('syndie.contracts.show',['contract'=>$objective->contract_id]);
    }

    public function close(Request $request, $objective)
    {
        $objective = SyndieContractObjective::findOrFail($objective);
        $contract = $objective->contract()->get();
        if($request->user()->cannot('contract_moderate') && $contract->contractee_id != $request->user()->id)
        {
            abort('403','You do not have the required permission');
        }

        $objective->status = "closed";
        $objective->save();

        return redirect()->route('syndie.contracts.show',['contract'=>$objective->contract_id]);
    }

    public function open(Request $request, $objective)
    {
        $objective = SyndieContractObjective::findOrFail($objective);
        $contract = $objective->contract()->get();
        if($request->user()->cannot('contract_moderate') && $contract->contractee_id != $request->user()->id)
        {
            abort('403','You do not have the required permission');
        }

        $objective->status = "open";
        $objective->save();

        return redirect()->route('syndie.contracts.show',['contract'=>$objective->contract_id]);
    }

    public function delete(Request $request, $objective)
    {
        $objective = SyndieContractObjective::findOrFail($objective);
        $contract = $objective->contract()->get();
        if($request->user()->cannot('contract_moderate') && $contract->contractee_id != $request->user()->id)
        {
            abort('403','You do not have the required permission');
        }

        $objective->status = "deleted";
        $objective->save();
        $objective->delete();

        return redirect()->route('syndie.contracts.show',['contract'=>$objective->contract_id]);
    }
}
