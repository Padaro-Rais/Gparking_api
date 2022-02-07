<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntrepriseParking extends Model
{
    protected $guarded  = [
        'id'
    ];

    public function parking()
    {
        return $this->belongsTo('App\Parking', 'parking_id');
    }

    public function entreprise()
    {
        return $this->belongsTo('App\Entreprise', 'entriprise_id');
    }
}
