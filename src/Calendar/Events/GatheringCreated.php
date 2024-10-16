<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Events;

use ArtisanBuild\Hallway\Calendar\Enums\InvitationLevels;
use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberRole;
use ArtisanBuild\VerbsFlux\Attributes\FluxDateTimeInput;
use ArtisanBuild\VerbsFlux\Attributes\FluxForm;
use ArtisanBuild\VerbsFlux\Attributes\FluxTextInput;
use Carbon\Carbon;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[FluxForm(
    submit_text: 'Create a Gathering',
    success: 'success',
)]

class GatheringCreated extends Event
{
    use AuthorizesBasedOnMemberRole;

    #[StateId(GatheringState::class)]
    public ?int $gathering_id = null;

    #[FluxTextInput(
        name: 'title',
        label: 'Title',
    )]
    public string $title;
    #[FluxTextInput(
        name: 'description',
        label: 'Description',
    )]
    public string $description;

    #[FluxDateTimeInput(
        name: 'start',
        label: 'Start',
        min: 'now',
        max: 'months:6',
    )]
    public Carbon $start;

    #[FluxTextInput(
        name: 'Duration',
        label: 'Duration in minutes',
    )]
    public int $duration;
    public ?Carbon $published_at = null;
    public ?Carbon $cancelled_at = null;
    public InvitationLevels $invitation_level;

    public function apply(GatheringState $state): void
    {
        $state->title = $this->title;
        $state->description = $this->description;
        $state->start = $this->start;
        $state->end = $this->start->copy()->addMinutes($this->duration);
        $state->published_at = $this->published_at;
        $state->cancelled_at = $this->cancelled_at;
        $state->invitation_level = $this->invitation_level;
    }

    public function handle(): void
    {
        // Most of yall don't get the picture unless the flash is on. - Lil Wayne
    }
}
