<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Rate limit
    |--------------------------------------------------------------------------
    |
    | Requests per minute allowed per caller address on the public JSON API.
    | Every response carries the current value in its X-RateLimit-Limit
    | header.
    |
    */

    'rate_limit' => (int) env('API_RATE_LIMIT', 120),

];
