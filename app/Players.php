<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Players extends Model
{
    protected $fillable = [
        'team_id', 
        'player_id', 
        'number_tshirt',
        'possition',
        'is_deleted'
    ];
}
