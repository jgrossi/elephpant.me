<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public function elephpantFrom()
    {
        return $this->belongsTo(Elephpant::class, 'elephpant_from_id');
    }

    public function elephpantTo()
    {
        return $this->belongsTo(Elephpant::class, 'elephpant_to_id');
    }

    public function userTo()
    {
        return $this->belongsTo(User::class, 'user_to_id');
    }
}
