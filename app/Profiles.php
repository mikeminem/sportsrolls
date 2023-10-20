<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    //
    protected $fillable = [
        'state_id',
        'municipality_id',
        'photo',
        'dob',
        'sex',
        'footware_meansure',
        'about_me',
        'tshirt_size',
        'favorite_number',
        'facebook',
        'twitter',
        'instagram',
        'emergency',
        'phone_emergency'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
