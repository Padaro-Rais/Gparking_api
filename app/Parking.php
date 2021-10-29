<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{


    protected $guarded  = [
        'id'
    ];

    public function entreprise()
    {
        return $this->belongsTo('App\Entreprise', 'entriprise_id');
    }

}
