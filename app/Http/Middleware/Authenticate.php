<?php

namespace App\Http\Middleware;

class Authenticate
{
    public function handle($request, $next)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return $next($request);
    }
}
