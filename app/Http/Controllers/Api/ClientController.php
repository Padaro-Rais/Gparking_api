<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ClientRessource;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ClientRessource::collection(
            client::where('archive',0)->get()
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
            'label' => 'required',
            'prix' => 'required',
        ]);


        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $client = new client();
        $client->label = $request->label;
        $client->prix = $request->prix;

        if (is_null($request->code)) {
            $client->code = client::getCode();
        }
        $client->save();
        return response()->json(new ClientRessource($client), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $args = array();
        $args['error'] = false;

        $data = client::find($id);
        $all = $request->all();
        if(is_null($data))
        {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        }
        else{
            $data->update($all);
            return response()->json(new ClientRessource($data), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\client  $client
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $data = client::find($id);
        $data->update(['archive' => 1]);
        return response()->json(['err' => false, 'success' => true, 'message' => 'parking archivée']);
    }
}
