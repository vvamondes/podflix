<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }

    public function playlists()
    {
        return $this->hasMany('App\Playlist');
    }

    public function programRequests()
    {
        return $this->hasMany('App\UserProgramRequest');
    }
    
}
