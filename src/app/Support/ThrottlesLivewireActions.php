<?php

namespace App\Support;

use Illuminate\Cache\RateLimiter;

trait ThrottlesLivewireActions
{
    protected function hitRateLimit(string $key, int $maxAttempts = 5, int $decaySeconds = 60): bool
    {
        $limiter = app(RateLimiter::class);
        $key = $key.'|'.request()->ip();

        if ($limiter->tooManyAttempts($key, $maxAttempts)) {
            return true;
        }

        $limiter->hit($key, $decaySeconds);

        return false;
    }

    protected function clearRateLimit(string $key): void
    {
        app(RateLimiter::class)->clear($key.'|'.request()->ip());
    }
}
