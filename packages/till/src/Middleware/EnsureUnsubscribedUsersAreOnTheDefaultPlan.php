<?php

namespace ArtisanBuild\Till\Middleware;

use ArtisanBuild\Till\Events\NewSubscriberAddedToDefaultPlan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUnsubscribedUsersAreOnTheDefaultPlan
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return $next($request);
        }

        // This mostly exists because some of our tests that came over from Jetstream use
        // factories to create users without team ids rather than firing the UserCreated event which
        // ensures that value is set. This block of code lets us keep those tests as they are for now.
        if (config('till.team_mode') && Auth::user()->current_team_id === null) {
            return $next($request);
        }

        if (Auth::user()->abilities() === []) {
            NewSubscriberAddedToDefaultPlan::commit(
                subscriber_id: config('till.team_mode')
                    ? Auth::user()->current_team_id
                    : Auth::id()
            );
        }

        return $next($request);
    }
}
