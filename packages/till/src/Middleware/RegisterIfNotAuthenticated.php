<?php

namespace ArtisanBuild\Till\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RegisterIfNotAuthenticated
{
    public function handle($request, Closure $next)
    {
        if (! Auth::check()) {
            // Store the intended URL in the session
            session(['url.intended' => url()->full()]);

            // Redirect to the registration page
            return redirect()->route('register');
        }

        return $next($request);
    }
}
