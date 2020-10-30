<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElephpantUser extends Model
{
    protected $casts = [
        'quantity' => 'integer',
    ];
}
