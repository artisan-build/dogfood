<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Middleware;

use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Payment\Enums\PaymentStates;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Session;
use Thunk\Verbs\State;

class GetCurrentActiveMemberFromSession
{
    public function handle($request, Closure $next)
    {
        if ( ! Auth::check()) {
            Context::add('active_member', $this->guest());
            return $next($request);
        }

        if (Auth::user()->hallway_members->isEmpty()) {
            Context::add('active_member', $this->guest());
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

    private function guest(): State
    {
        return new class () extends MemberState {
            public MemberRoles $role = MemberRoles::Guest;
            public PaymentStates $payment_state = PaymentStates::Free;
        };
    }

}
