<?php

declare(strict_types=1);

namespace App\Providers;

use App\ElephpantUser;
use App\Observers\ElephpantUserObserver;
use App\Observers\UserObserver;
use App\Queries\TradingUsersQuery;
use App\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(TradingUsersQuery::class);
    }

    public function boot(): void
    {
        User::observe(UserObserver::class);
        ElephpantUser::observe(ElephpantUserObserver::class);

        $this->configureRateLimiting();
    }

    /**
     * The API is unauthenticated, so the caller's address is the only thing to key on.
     * Going over the limit is answered in JSON rather than in the HTML error page a
     * browser would want.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(config('api.rate_limit'))
            ->by($request->ip())
            ->response(fn (Request $request, array $headers) => response()->json([
                'message' => 'Too many requests. Wait the number of seconds in the Retry-After header.',
            ], 429, $headers)));
    }
}
