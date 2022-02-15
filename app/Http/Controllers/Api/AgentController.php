<?php

namespace App\Http\Controllers\Api;
use App\Agent;
use App\Http\Controllers\Controller;
use App\Http\Resources\AgentRessource;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AgentRessource::collection(
            Agent::where('status',1)->orderBy('created_at', 'DESC')->get()
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'code' => 'nulable',
            'nom' => 'required',
            'prenoms' => 'required',
            'adresse' => 'required',
            'telephone' => 'required',
            'matricule_ent' => 'required',
            'parking_id' => 'required',
            'entriprise_id' => 'required',
            'parking_id' => 'required_with:parkings|exists:parkings,id',
            'entriprise_id' => 'required_with:entreprises|exists:entreprises,id',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $Agent = new Agent();
        $Agent->code = $request->code;
        $Agent->nom = $request->nom;
        $Agent->prenoms = $request->prenoms;
        $Agent->adresse = $request->adresse;
        $Agent->telephone = $request->telephone;
        $Agent->matricule_ent = $request->matricule_ent;
        $Agent->parking_id = $request->parking_id;
        $Agent->entriprise_id = $request->entriprise_id;

        if (is_null($request->code)) {
            $Agent->code = rand();
        }
        $Agent->save();
        return response()->json(new AgentRessource($Agent), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agent  $Agent
     * @return \Illuminate\Http\Response
     */
    public function show(Agent $Agent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agent  $Agent
     * @return \Illuminate\Http\Response
     */
    public function edit(Agent $Agent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Agent  $Agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $args = array();
        $args['error'] = false;

        $data = Agent::find($id);
        $all = $request->all();
        if(is_null($data))
        {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        }
        else{
            $data->update($all);
            return response()->json(new AgentRessource($data), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Agent  $Agent
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $data = Agent::find($id);
        $data->update(['archive' => 1]);
        return response()->json(['err' => false, 'success' => true, 'message' => 'parking archivée']);
    }



    public function clientagent($id)
    {
        return AgentRessource::collection(
            Agent::where('status',1)->where('entriprise_id',$id)->orderBy('created_at', 'DESC')->get()
        );
    }
}
