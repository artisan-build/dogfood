<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Events;

use ArtisanBuild\Adverbs\Traits\SimpleApply;
use ArtisanBuild\Hallway\Calendar\Enums\InvitationLevels;
use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberState;
use Carbon\Carbon;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class GatheringUpdated extends Event
{
    use AuthorizesBasedOnMemberState;
    use SimpleApply;

    public array $authorized_member_roles = [
        MemberRoles::Owner,
        MemberRoles::Admin,
    ];

    #[StateId(GatheringState::class)]
    public int $gathering_id;

    public ?string $title = null;
    public ?string $description = null;
    public ?Carbon $start = null;
    public ?Carbon $end = null;
    public ?Carbon $published_at = null;
    public ?Carbon $cancelled_at = null;
    public ?InvitationLevels $invitation_level = null;



    public function handle(): void
    {
        // Most of yall don't get the picture unless the flash is on. - Lil Wayne
    }
}
