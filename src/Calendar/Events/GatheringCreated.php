<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Events;

use ArtisanBuild\Hallway\Calendar\Enums\InvitationLevels;
use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberState;
use ArtisanBuild\VerbsFlux\Attributes\EventForm;
use ArtisanBuild\VerbsFlux\Attributes\EventInput;
use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use Carbon\Carbon;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[EventForm(
    submit_text: 'Create New Gathering',
)]
class GatheringCreated extends Event
{
    use AuthorizesBasedOnMemberState;

    public array $authorized_member_roles = [
        MemberRoles::Owner,
        MemberRoles::Admin,
    ];

    #[StateId(GatheringState::class)]
    public ?int $gathering_id = null;


    #[EventInput(
        type: InputTypes::Text,
    )]
    public string $title;

    #[EventInput(
        type: InputTypes::Textarea,
        params: ['rows' => 'auto'],
    )]
    public string $description;

    #[EventInput(
        type: InputTypes::DatetimeLocal,
        params: ['min' => 'now', 'max' => 'months:6'],
    )]
    public Carbon $start;

    #[EventInput(
        type: InputTypes::Number,
        params: ['min' => 5, 'max' => 120],
        description: 'Length of meeting in minutes',
        suffix: 'Minutes',
    )]
    public int $duration = 30;
    public ?Carbon $published_at = null;
    public ?Carbon $cancelled_at = null;
    public InvitationLevels $invitation_level = InvitationLevels::Free;

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

}
