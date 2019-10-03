<?php

namespace App;

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
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
