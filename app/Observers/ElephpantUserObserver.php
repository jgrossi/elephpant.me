<?php

namespace App\Observers;

use App\ElephpantUser;

class ElephpantUserObserver
{
    public function saved(ElephpantUser $elephpantUser): void
    {
        if ($elephpantUser->quantity <= 0) {
            $elephpantUser->delete();
        }
    }
}
