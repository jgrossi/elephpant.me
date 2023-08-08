<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('nonPrototype', function (Builder $builder) {
            $builder->where('prototype', 0);
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query->where('name', 'LIKE', '%'.$request->get('q').'%')
            ->orWhere('description', 'LIKE', '%'.$request->get('q').'%')
            ->orWhere('sponsor', 'LIKE', '%'.$request->get('q').'%')
            ->orWhere('year', 'LIKE', '%'.$request->get('q').'%');
    }
}
