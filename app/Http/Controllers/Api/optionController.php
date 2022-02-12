<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Parking;
use App\Entreprise;
use DB;
class optionController extends Controller
{
    
    public function parking($id)
    {
      

        $result = DB::table('entreprise_parkings')
    ->join('parkings', 'parkings.id', '=', 'entreprise_parkings.parking_id')
    ->where('entreprise_parkings.entriprise_id', $id)
    ->get();
        return response()->json($result);
    }


    public function entreprise()
    {
         $p =  Entreprise::get();

        return response()->json($p);
    }



}
