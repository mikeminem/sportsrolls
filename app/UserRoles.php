<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $table = 'user_roles';

    public $timestamps = false;

    protected $fillable = ['user_id', 'rol_id'];

    protected $casts = ['user_id' => 'integer', 'rol_id' => 'integer'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Roles::class, 'rol_id');
    }
}
