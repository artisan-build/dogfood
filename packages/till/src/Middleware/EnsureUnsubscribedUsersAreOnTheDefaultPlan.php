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
        if (!Auth::check()) {
            return $next($request);
        }

        if (!Auth::user()->abilities() === null) {
            NewSubscriberAddedToDefaultPlan::commit(
                subscriber_id: config('till.team_mode')
                    ? Auth::user()->current_team_id
                    : Auth::id()
            );
        }

        return $next($request);
    }
}
