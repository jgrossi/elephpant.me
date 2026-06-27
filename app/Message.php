<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    #[\Override]
    protected $fillable = ['sender_id', 'receiver_id', 'message'];
}
