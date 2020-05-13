<?php

namespace App\Http\Controllers\Server;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExterminatusController extends Controller
{
    public function index(){
        return view('server.exterminatus.index',array("submitted"=>FALSE));
    }

    public function exterminate(Request $request){
        return view('server.exterminatus.index',array("submitted"=>TRUE));
    }
}
