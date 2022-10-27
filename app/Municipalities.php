<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipalities extends Model
{
    //
    protected $fillable = [
        'short_name', 'name', 'is_deleted', 'active', 'state_id'
    ];
}
