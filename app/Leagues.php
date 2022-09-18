<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leagues extends Model
{
    //
    protected $fillable = [
        'name', 'slogan', 'summary', 'president', 'secretary', 'treasurer', 'referee_main'
    ];


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
