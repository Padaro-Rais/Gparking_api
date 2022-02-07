<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionRessource;
use Illuminate\Support\Facades\Validator;



class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TransactionRessource::collection(
            Transaction::where('archive',0)->orderBy('created_at', 'DESC')->get()
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
            'num_plaque' => 'required',
            'date' => 'nullable',
            'agent_id' => 'required',
            'parking_id' => 'required',
            'client_id' => 'required',
            'entriprise_id' => 'required',
            // 'agent_id' => 'required_with:agents|exists:agents,id',
            // 'client_id' => 'required_with:clients|exists:clients,id',

        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }
        date('d/m/Y');

        $Transaction = new Transaction();
        $Transaction->code = $request->code;
        $Transaction->num_plaque = $request->num_plaque;
        $Transaction->agent_id = $request->agent_id;
        $Transaction->client_id = $request->client_id;
        $Transaction->parking_id = $request->parking_id;
        $Transaction->entriprise_id = $request->entriprise_id;


        if (is_null($request->code)) {
            $Transaction->code = uniqid();
        }
        if (is_null($request->date)) {
            $Transaction->date = date('d/m/Y');
        }
        $Transaction->save();
        return response()->json(new TransactionRessource($Transaction), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $Transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $Transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $Transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $Transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $Transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $args = array();
        $args['error'] = false;

        $data = Transaction::find($id);
        $all = $request->all();
        if(is_null($data))
        {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        }
        else{
            $data->update($all);
            return response()->json(new TransactionRessource($data), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $Transaction
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $data = Transaction::find($id);
        $data->update(['archive' => 1]);
        return response()->json(['err' => false, 'success' => true, 'message' => 'parking archivée']);
    }


    public function clienttransaction($id)
    {
        return TransactionRessource::collection(
            Transaction::where('archive',0)->where('entriprise_id',$id)->orderBy('created_at', 'DESC')->get()
        );
    }
}
