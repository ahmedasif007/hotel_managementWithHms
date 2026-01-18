<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->resolveRequestSignature($request);
        $limit = config('app.rate_limit', 60);
        $decay = 60; // 1 minute

        if ($this->limiter->tooManyAttempts($key, $limit, $decay)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
            ], 429);
        }

        $this->limiter->hit($key, $decay);

        $response = $next($request);

        return $response->header('X-RateLimit-Limit', $limit)
            ->header('X-RateLimit-Remaining', $this->limiter->retriesLeft($key, $limit));
    }

    protected function resolveRequestSignature(Request $request): string
    {
        // For authenticated users, use user ID
        if ($request->user()) {
            return sha1($request->user()->id . '|' . $request->ip());
        }

        // For guests, use IP address
        return sha1($request->ip());
    }
}
