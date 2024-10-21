<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Middleware;

use ArtisanBuild\Hallway\Members\States\MemberState;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Session;

class GetCurrentActiveMemberFromSession
{
    public function handle($request, Closure $next)
    {
        if ( ! Auth::check()) {
            return $next($request);
        }

        if (Auth::user()->hallway_members->isEmpty()) {
            return $next($request);
        }

        if ( ! Session::has('active_member_id')) {
            $session_id = Auth::user()->hallway_members->first()->id;

            if (is_integer($session_id)) {
                Session::put('active_member_id', $session_id);
            }
        }

        Context::add('active_member', MemberState::loadOrFail(Session::get('active_member_id')));

        return $next($request);
    }

}
