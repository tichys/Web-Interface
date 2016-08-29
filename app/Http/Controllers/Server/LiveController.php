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
namespace App\Http\Controllers\Server;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Server\ServerQuery;

class LiveController extends Controller
{
    public function index()
    {
        return view('server.live.index');
    }

    public function getComoptions()
    {
        return view('server.live.coms');
    }

    public function getGhostoptions()
    {
        return view('server.live.ghosts');
    }

    public function getFaxmachines()
    {
        $query = New ServerQuery();
        $query->setUp("localhost","1234");
        $query->runQuery([
            "query"=>"getfaxmachines"
        ]);

        if($query->reply_status == "200")
            return $query->response["data"];
        else
            abort($query->reply_status);
    }

    public function getGhosts()
    {
        $query = New ServerQuery();
        $query->setUp("localhost","1234");
        $query->runQuery([
            "query"=>"getghosts"
        ]);

        if($query->reply_status == "200")
            return $query->response["data"];
        else
            abort($query->reply_status);
    }

    public function postSendfax(Request $request)
    {
        $query = New ServerQuery;
        $query->setUp("localhost","1234");

        $query->runQuery([
            "query" => "sendfax",
            "senderkey" => $request->user()->username,
            "title" => $request->input('faxtitle'),
            "body" => nl2br($request->input('faxbody')),
            "announce" => $request->input('faxannounce'),
            "target" => $request->input('faxtargets')
        ]);
        if($query->reply_status == "200")
            return $query->response["data"];
        else
            abort($query->reply_status);
    }

    public function postSendreport(Request $request)
    {
        $query = New ServerQuery;
        $query->setUp("localhost","1234");

        $query->runQuery([
            "query" => "sendcommandreport",
            "senderkey" => $request->user()->username,
            "title" => $request->input('reporttitle'),
            "body" => $request->input('reportbody'),
            "announce" => $request->input('reportannounce'),
            "type" => $request->input('reporttype'),
            "sendername" => $request->input('reportsender'),
        ]);
        if($query->reply_status == "200")
            return $query->response["data"];
        else
            abort($query->reply_status);
    }

    public function postGrantrespawn(Request $request)
    {
        $query = New ServerQuery;
        $query->setUp("localhost","1234");

        $query->runQuery([
            "query" => "grantrespawn",
            "senderkey" => $request->user()->username,
            "target" => $request->input('target')
        ]);
        if($query->reply_status == "200")
            return $query->response["data"];
        else
            abort($query->reply_status);
    }
}
