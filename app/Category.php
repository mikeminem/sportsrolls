<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'sport_id',
        'name',
        'age_initial',
        'age_final',
        'overview',
        'user_id',
        'show_stats',
        'tournament_id',
        'has_groups'
    ];
}
