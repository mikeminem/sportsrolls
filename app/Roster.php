<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    protected $fillable = [
        'team_id',
        'user_id',
        'player_id',
        'number',
        'positions',
        'status',
        'category_id',
        'is_deleted',
        'tournament_id'
    ];

    public function player()
    {
        return $this->belongsTo('App\Player');
    }
}

