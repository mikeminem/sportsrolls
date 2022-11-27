<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournaments extends Model
{
    //
    protected $fillable = [
        'code', 'title', 'summary', 'contacts', 'header_image', 'convocatory', 'is_deleted', 'status', 'start_day', 'sport_id', 'clicks', 'slug'
    ];


    public function sport()
    {
        return $this->belongsTo('App\Sports');
    }

    public function journeys()
    {
        return $this->hasMany('App\Journeys', "tournament_id","id" );
    }

    public function games()
    {
        return $this->hasMany('App\Games');
    }
}
