<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Events;

use ArtisanBuild\Hallway\Calendar\Enums\InvitationLevels;
use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use ArtisanBuild\Jetstream\Traits\AuthorizesBasedOnUserRole;
use Carbon\Carbon;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class GatheringCreated extends Event
{
    use AuthorizesBasedOnUserRole;

    #[StateId(GatheringState::class)]
    public int $gathering_id;

    public string $title;
    public string $description;
    public Carbon $start;
    public Carbon $end;
    public ?Carbon $published_at = null;
    public ?Carbon $cancelled_at = null;
    public InvitationLevels $invitation_level;

    public function apply(GatheringState $state): void
    {
        $state->title = $this->title;
        $state->description = $this->description;
        $state->start = $this->start;
        $state->end = $this->end;
        $state->published_at = $this->published_at;
        $state->cancelled_at = $this->cancelled_at;
        $state->invitation_level = $this->invitation_level;
    }

    public function handle(): void
    {
        // Most of yall don't get the picture unless the flash is on. - Lil Wayne
    }
}
