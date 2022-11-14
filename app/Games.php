<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    //
    protected $fillable = [
        'game_day', 
        'game_time', 
        'notes', 
        'status', 
        'type', 
        'branch', 
        'score_team1', 
        'score_team2', 
        'user_id', 
        'tournament_id', 
        'category_id',
        'journey_id',
        'team1_id',
        'team2_id',
        'is_deleted'
    ];

    public function tournament()
    {
        return $this->belongsTo('App\Tournaments');
    }
}
