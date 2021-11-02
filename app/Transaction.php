<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $guarded  = [
        'id'
    ];

    public function agent()
    {
        return $this->belongsTo('App\Agent', 'agent_id');
    }
    public function client()
    {
        return $this->belongsTo('App\client', 'client_id');
    }
}
