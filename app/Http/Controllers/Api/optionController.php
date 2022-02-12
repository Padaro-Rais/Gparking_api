<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Parking;
use App\Entreprise;

class optionController extends Controller
{
    
    public function parking($id)
    {
      
        $p = Parking::where('status',1)->where('entriprise_id',$id)->orderBy('created_at', 'DESC')->get();
        return response()->json($p);
    }


    public function entreprise()
    {
         $p =  Entreprise::get();

        return response()->json($p);
    }



}
