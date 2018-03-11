<?php

namespace App;

use Illuminate\Notifications\Notifiable;
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
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * get the route key name
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    public function treads()
    {
        return $this->hasMany(Tread::class)->latest();
    }

    public function activity()
    {
       return $this->hasMany(Activity::class);
    }

    public function visitedTreadCacheKey($tread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $tread->id);
    }

    public function read($tread)
    {
        cache()->forever(
            $this->visitedTreadCacheKey($tread),
            \Carbon\Carbon::now());
    }
}
