<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'phone', 'role', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pages()
    {
        return $this->hasMany('App\WebPage');
    }

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'user_roles', 'user_id', 'rol_id')
            ->withPivot('id')->where('roles.is_deleted', 0);
    }

    public function league()
    {
        return $this->hasOne('App\Leagues');
    }

    public function tournament()
    {
        return $this->hasMany('App\Tournaments');
    }

    public function rosters()
    {
        return $this->hasMany('App\Roster');
    }

    public function profile()
    {
        return $this->hasOne('App\Profiles');
    }

    public static function getRole($role) {
        switch ($role) {
            case "Admin":
                return "Administrador";
                break;

            case "Promoter":
                return "Promotor";
                break;
            default:
                return "Jugador";
                break;
        }
    }
}
