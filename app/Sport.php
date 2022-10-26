<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $fillable = [
        'code', 'name', 'is_deleted', 'image'
    ];

    public function tournament()
    {
        return $this->hasMany('App\Tournaments');
    }

}
