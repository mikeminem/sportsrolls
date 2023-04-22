<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    //
    protected $fillable = [
        'name', 'logo', 'photo', 'description', 'branch', 'user_id', 'tournament_id', 'sport_id', 'category_id', 'is_deleted', 'clicks', 'prints', 
        'contact', 'contact_phone', 'status'
    ];
}
