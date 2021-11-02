<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $guarded  = [
        'id'
    ];

    public function parking()
    {
        return $this->belongsTo('App\Parking', 'parking_id');
    }
}
