<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Traits;

use ArtisanBuild\FatEnums\Attributes\VerbsCan;
use ArtisanBuild\Hallway\Members\States\MemberState;
use Illuminate\Support\Facades\Auth;
use ReflectionClassConstant;
use Throwable;

trait AuthorizesBasedOnMemberRole
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


        $state = Auth::user()?->hallway_members->first()->verbs_state();


        if ( ! $state instanceof MemberState) {
            return false;
        }

        $events = (new ReflectionClassConstant($state->role, $state->role->name))
            ->getAttributes(VerbsCan::class)[0]->newInstance()->events;


        return in_array(get_class($this), $events, true);
    }
}
