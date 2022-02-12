<?php

namespace App\Http\Controllers\Auth;

use App\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AgentRessource;

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
        $v = Validator::make($request->all(), [
            'code' => 'required',
            'matricule_ent' => 'required',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $agent = Agent::where(['code' => $request->code ,'matricule_ent' => $request->matricule_ent])->get();

        if (empty($agent)){
            return response()->json(['succes' => false ,'message' => "credentiels Incorects"]);
        }else{
            return response()->json($agent);
        }


    }

  
}
