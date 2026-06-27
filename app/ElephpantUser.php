<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $quantity
 */
class ElephpantUser extends Model
{
    #[\Override]
    protected $table = 'elephpant_user';

    #[\Override]
    protected $fillable = ['user_id', 'elephpant_id', 'quantity'];

    #[\Override]
    protected $casts = [
        'quantity' => 'integer',
    ];
}
