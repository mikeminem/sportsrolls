<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        //'municipality_id',
        'blood_type',
        'access_pin'
    ];


    public function roster(): HasMany
    {
        return $this->hasMany('App\Roster');
    }
}
