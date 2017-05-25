<?php

namespace App\Http\Controllers\Server;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PDO;

class WhitelistLogController extends Controller
{
    public function getLog(Request $request)
    {
        if($request->user()->cannot('server_whitelist_log_show'))
        {
            abort('403','You do not have the required permission');
        }

        $logs = DB::connection('server')->table('whitelist_log')->orderby('datetime','desc')->get();

        return view('server.whitelist.log', [
            'logs' => $logs,
        ]);
    }
}
