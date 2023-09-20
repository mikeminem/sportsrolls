<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stadiums extends Model
{
    //
    protected $fillable = [
        'code', 'name', 'address', 'manager', 'phone', 'notes', 'is_deleted', 'user_id'
    ];
}
