<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Agent extends Model
{
    protected $guarded  = [
        'id'
    ];

    public static function getCode()
    {
        $key = Str::lower(Str::random(32));
        if(static::where(['code' => $key])->first())
            return static::getCode();
        return $key;
    }


    public function parking()
    {
        return $this->belongsTo('App\Parking', 'parking_id');
    }
}
