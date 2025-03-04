<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameLogs extends Model
{
    //
    protected $fillable = [
        'player_id',
        'action_id',
        'game_id',
        'tournament_id',
        'comment',
        'is_deleted',
        'value',
        'time',
        'period',
    ];

    public function game() {
        return $this->belongsTo('App\Game');
    }

}
