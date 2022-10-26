<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    //
    protected $fillable = [
        'short_name', 'name', 'is_deleted', 'active'
    ];
}
