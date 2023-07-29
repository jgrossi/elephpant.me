<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Elephpant extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'sponsor',
        'year',
        'image',
        'prototype',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('nonPrototype', function (Builder $builder) {
            $builder->where('prototype', 0);
        });
    }
}
