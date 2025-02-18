<?php

namespace ArtisanBuild\Till\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TillAbilities
{
    public function handle(Request $request, Closure $next, string $params)
    {
        foreach (explode(',', $params) as $ability) {
            $can = Str::of($ability)->camel();
            // Calculate namespace based on till configuration

            // Throw if class does not exist

            // Check
        }

        return $next($request);
    }
}
