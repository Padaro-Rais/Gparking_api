<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Parking;
use Illuminate\Http\Request;
use App\Http\Resources\ParkingRessource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ParkingRessource::collection(
            Parking::where('status',1)->orderBy('created_at', 'DESC')->get()
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
            'nom' => 'required',
            'adresse' => 'required',
            'quartier' => 'required',
            'ville' => 'required',
        
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $parking = new Parking();
        $parking->code = $request->code;
        $parking->nom = $request->nom;
        $parking->adresse = $request->adresse;
        $parking->ville = $request->ville;
        $parking->quartier = $request->quartier;
        if($request->entriprise_id) $parking->entriprise_id = $request->entriprise_id;
        if (is_null($request->code)) {
            $parking->code = Parking::getCode();
        }
        $parking->save();

        return response()->json(new ParkingRessource($parking), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function show(Parking $parking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function edit(Parking $parking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $args = array();
        $args['error'] = false;

        $data = Parking::find($id);
        $all = $request->all();
        if(is_null($data))
        {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        }
        else{
            $data->update($all);
            return response()->json(new ParkingRessource($data), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Parking::find($id);
        $data->update(['status' => 0]);
        return response()->json(['err' => false, 'success' => true, 'message' => 'parking archivée']);
    }


    public function clientparking($id)
    {
        return ParkingRessource::collection(
            Parking::where('status',1)->where('entriprise_id',$id)->orderBy('created_at', 'DESC')->get()
        );
    }

}
