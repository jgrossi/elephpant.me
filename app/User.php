<?php

namespace App;

use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'country_code', 'twitter', 'username'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameLettersAttribute()
    {
        $name = $this->getAttribute('name');
        $nameSeparatedBySpace = collect(explode(' ', $name));

        if ($nameSeparatedBySpace->count() === 1) {
            return $nameSeparatedBySpace->first()[0];
        }

        return $nameSeparatedBySpace->first()[0] . $nameSeparatedBySpace->last()[0];
    }

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

    public function elephpantsWithQuantity(): Collection
    {
        return $this->elephpants
            ->mapWithKeys(function (Elephpant $elephpant) {
                return [
                    $elephpant->id => $elephpant->pivot->quantity,
                ];
            });
    }

    public function adopt(Elephpant $elephpant, int $quantity)
    {
        $exists = $this->elephpants()
            ->whereElephpantId($elephpant->id)
            ->exists();

        if ($exists) {
            $quantity > 0 ?
                $this->elephpants()->updateExistingPivot($elephpant->id, compact('quantity'), false) :
                $this->elephpants()->detach($elephpant->id);

                return;
        }

        $this->elephpants()->attach($elephpant->id, compact('quantity'));
    }

    public function avatar(): string
    {
        return Gravatar::get($this->email);
    }

    public static function generateUsername(User $user): string
    {
        $username = $user->twitter ?: Str::slug($user->name);
        $count = 1;

        while(User::whereUsername($username)->exists()) {
            $username = $username . $count;
            $count++;
        }

        return $username;
    }
}
