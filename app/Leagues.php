<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leagues extends Model
{
    //
    protected $fillable = [
        'name', 
        'slogan', 
        'summary', 
        'facebook', 
        'instagram', 
        'twitter', 
        'footer1', 
        'website', 
        'watsapp', 
        'sport_id', 
        'state_id', 
        'municipality_id',
    ];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sport()
    {
        return $this->belongsTo('App\Sports');
    }
}
