<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Traits;

use ArtisanBuild\Hallway\Members\States\MemberState;
use Illuminate\Support\Facades\Context;
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

        $member = Context::get('active_member');

        if (! $member instanceof MemberState) {
            return false;
        }

        return $member->can($this);
    }
}
