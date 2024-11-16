<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Players extends Model
{
    protected $fillable = [
        'curp',
        'names',
        'last_name1',
        'last_name2',
        'access_pin',
        'email',
        'phone',
        'dob',
        'gender',
        'emergency_name',
        'emergency_phone',
        'status',
        'photo',
        'is_validated',
        'is_deleted',
        'state_id',
        'municipality_id',
        'blod_type'
    ];
}
