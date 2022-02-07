<?php

namespace App\Http\Controllers\Api;

use App\EntrepriseParking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\EntrepriseParkingResources;
use Illuminate\Support\Facades\Validator;


class EntrepriseParkingController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EntrepriseParkingResources::collection(
            EntrepriseParking::orderBy('created_at', 'DESC')->get()
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
            'entriprise_id' => 'required',
            'parking_id' => 'required',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $Eparking = new EntrepriseParking();
        $Eparking->entriprise_id = $request->entriprise_id;
        $Eparking->parking_id = $request->parking_id;
        $Eparking->save();
        return response()->json(new EntrepriseParkingResources($Eparking), 201);
    }

  
    
    public function destroy($id)
    {
        $data = EntrepriseParking::find($id);
        $data->delete();
        return response()->json(['err' => false, 'success' => true, 'message' => 'afectation archivée']);
    }


    public function clientparking($id)
    {
        return EntrepriseParkingResources::collection(
            EntrepriseParking::where('entriprise_id',$id)->orderBy('created_at', 'DESC')->get()
        );
    }
}
