<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElephpantUser extends Model
{
    protected $table = 'elephpant_user';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function elephpant()
    {
        return $this->belongsTo(Elephpant::class);
    }
}
