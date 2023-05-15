<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    //
    protected $fillable = [
        'name', 'notes', 'tournament_id',
    ];
}
