<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebPage extends Model
{
    protected $table = 'webpages';
    
    protected $fillable = [
        'used_id',
        'tournament_id',
        'page_category_id',
        'title',
        'slug',
        'description',
        'image',
        'content',
        'status',
        'type',
        'location',
        'is_deleted',
        'facebook',
        'instagram',
        'twitter'
    ];
}
