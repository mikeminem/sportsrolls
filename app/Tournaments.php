<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournaments extends Model
{
    //
    protected $fillable = [
        'code',
        'title',
        'summary',
        'contacts',
        'header_image',
        'convocatory',
        'is_deleted',
        'status',
        'start_day',
        'sport_id',
        'clicks',
        'slug',
        'enable_points_system',
        'winner_points',
        'loser_points',
        'draw_points',
        'winner_points_penalties',
        'loser_points_penalties',
        'enable_penalties_scoring',
        'enable_enrollment',
        'period_time',
        'period_num'
    ];


    public function sport()
    {
        return $this->belongsTo('App\Sports');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
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
