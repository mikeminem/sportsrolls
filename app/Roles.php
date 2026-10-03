<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';

    public $timestamps = false;

    protected $fillable = ['name', 'description', 'is_deleted', 'is_default'];

    protected $attributes = ['is_deleted' => 0, 'is_default' => 0];

    protected $casts = ['is_deleted' => 'integer', 'is_default' => 'integer'];
}
