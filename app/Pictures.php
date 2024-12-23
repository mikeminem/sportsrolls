<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pictures extends Model
{
    protected $table = 'pictures';

    protected $fillable = [
        'tournament_id',
        'title',
        'slug',
        'journey_id',
        'game_id',
        'description',
        'is_deleted',
        'photo',
        'likes',
        'dislikes',
        'happy',
        'love',
        'status',
        'user_id',
        'prints',
        'visits'
    ];

}
