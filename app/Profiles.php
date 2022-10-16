<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    //
    protected $fillable = [
        'state_id', 'municipality_id', 'photo', 'dob', 'sex', 'footware_meansure', 'about_me', 'tshirt_size', 'favorite_number', 'facebook', 'twitter', 'instagram'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
