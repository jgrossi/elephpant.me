<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $quantity
 */
class ElephpantUser extends Model
{
    protected $table = 'elephpant_user';

    protected $fillable = ['user_id', 'elephpant_id', 'quantity'];

    protected $casts = [
        'quantity' => 'integer',
    ];
}
