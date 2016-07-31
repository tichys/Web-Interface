<?php

namespace App\Http\Controllers\Syndie;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SyndieContract;
use App\Models\SyndieContractComment;
use App\Models\SyndieContractObjective;

class ContractComment extends Controller
{
    public function getAdd(Request $request, $contract)
    {
        $contract = SyndieContract::findOrFail($contract);
        $objectives = SyndieContractObjective::where('contract_id', '=', $contract->contract_id)->get();

        return view('syndie.comment.add',["contract"=>$contract, 'objectives' => $objectives]);
    }
}
