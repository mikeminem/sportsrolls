<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowTeams extends Model
{
    //
    protected $fillable = [
        'follower_id',
        'follow_tournament_id',
        'follow_category_id',
        'follow_team_id',
    ];
}
