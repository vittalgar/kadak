<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter as Limiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimiter
{
    protected Limiter $limiter;

    public function __construct(Limiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $key = 'default', $maxAttempts = 5, $decayMinutes = 1): Response
    {
        // Use the user's IP address as a unique identifier
        $limiterKey = $key . '|' . $request->ip();

        if ($this->limiter->tooManyAttempts($limiterKey, $maxAttempts)) {
            // If the user has made too many requests, return a 429 error
            return new Response('Too Many Attempts.', 429);
        }

        // Hit the limiter for the current request
        $this->limiter->hit($limiterKey, $decayMinutes * 60);

        // If the request is allowed, proceed
        return $next($request);
    }
}
