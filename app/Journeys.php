<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journeys extends Model
{
    //
    protected $fillable = [
        'name', 'notes', 'start_at', 'finish_at', 'tournament_id', 'sport_id', 'is_deleted', 'status'
    ];
}
