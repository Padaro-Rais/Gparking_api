<?php

namespace App\Http\Controllers\Auth;

use App\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class logMobileController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // $v = Validator::make($request->all(), [
        //     'code' => 'required',
        //     'matricule_ent' => 'required',
        // ]);

        // if ($v->fails()) {
        //     $args['error'] = true;
        //     $args['validator'] = $v->errors();
        //     return response()->json($v->errors(), 400);
        // }

        $agent = Agent::get();

        return response()->json($agent);

    }

  
}
