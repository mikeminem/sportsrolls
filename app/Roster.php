<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    protected $fillable = [
        'team_id',
        'user_id',
        'number',
        'positions',
        'status',
        'category_id',
        'is_deleted',
        'tournament_id',
        'name',
        'last_name',
        'phone',
        'emergency_name',
        'emergency_phone',
        'photo',
        'dob',
        'verified',
        'license'
    ];

    // public function player()
    // {
    //     return $this->belongsTo('App\Player');
    // }
}

