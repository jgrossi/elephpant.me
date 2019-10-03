<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeNotLoggedIn(Builder $query)
    {
        return $query->where('id', '<>', auth()->id());
    }

    public function elephpants()
    {
        return $this->belongsToMany(Elephpant::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function elephpantsToTrade()
    {
        return $this->elephpants()
            ->wherePivot('quantity', '>', 1);
    }
}
