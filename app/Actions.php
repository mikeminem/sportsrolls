<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actions extends Model
{
    protected $fillable = [
        'sport_id', 
        'name', 
        'label', 
        'scoring', 
        'value'
    ];
}
