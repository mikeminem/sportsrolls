<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageCategory extends Model
{
    protected $table = 'page_category';

    protected $fillable = [
        'name',
        'icon',
        'description',
        'keywords',
        'image',
        'user_id',
        'is_deleted',
        'slug',
        'status'
    ];
}
