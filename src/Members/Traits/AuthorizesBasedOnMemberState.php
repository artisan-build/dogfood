<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Traits;

use ArtisanBuild\Hallway\Members\States\MemberState;
use Illuminate\Support\Facades\Context;
use ReflectionClass;
use Throwable;

trait AuthorizesBasedOnMemberState
{
    /**
     * @throws Throwable
     */
    public function authorize(): bool
    {
        // Allow the seeder to use these methods
        if (app()->runningInConsole() && app()->isLocal()) {
            return true;
        }


        $state = Context::get('active_member');


        if ( ! $state instanceof MemberState) {
            return false;
        }

        $event = new ReflectionClass($this);

        if ($event->hasProperty('authorized_member_roles')) {

            if ( ! in_array($state->role, $event->getProperty('authorized_member_roles')->getDefaultValue(), true)) {
                return false;
            }
        }

        if ($event->hasProperty('authorized_payment_states')) {
            if ( ! in_array($state->payment_state, $event->getProperty('authorized_payment_states')->getDefaultValue(), true)) {
                return false;
            }
        }

        return true;
    }
}
