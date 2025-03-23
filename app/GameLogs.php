<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameLogs extends Model
{
    //
    protected $table = 'gamelogs';

    protected $fillable = [
        'player_id',
        'team_id',
        'action_id',
        'game_id',
        'tournament_id',
        'comment',
        'is_deleted',
        'value',
        'time',
        'period',
        'status',
    ];

    public function game() {
        return $this->belongsTo('App\Game');
    }

}
