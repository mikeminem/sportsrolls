<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournaments extends Model
{
    //
    protected $fillable = [
        'title', 'summary', 'contacts', 'header_image', 'convocatory', 'is_deleted', 'status', 'start_day'
    ];
}
