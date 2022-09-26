<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    //
    protected $fillable = [
        'state_id', 'municipality_id', 'photo', 'dob', 'sex', 'height', 'weight', 'footware_meansure', 'about_me'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
