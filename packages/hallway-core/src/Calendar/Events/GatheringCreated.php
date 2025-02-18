<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Events;

use ArtisanBuild\Adverbs\Traits\ReturnsModelInstanceOnHandle;
use ArtisanBuild\Hallway\Calendar\Enums\InvitationLevels;
use ArtisanBuild\Hallway\Calendar\States\CalendarRangeState;
use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use ArtisanBuild\Hallway\Members\Actions\GetMemberTimeZone;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberState;
use ArtisanBuild\VerbsFlux\Actions\Common\AllTimezoneIdentifiers;
use ArtisanBuild\VerbsFlux\Attributes\EventForm;
use ArtisanBuild\VerbsFlux\Attributes\EventInput;
use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use Carbon\Carbon;
use Thunk\Verbs\Attributes\Autodiscovery\AppliesToSingletonState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[AppliesToSingletonState(CalendarRangeState::class)]
#[EventForm(
    submit_text: 'Create New Gathering',
    has_time_machine: true,
)]
class GatheringCreated extends Event
{
    use AuthorizesBasedOnMemberState;
    use ReturnsModelInstanceOnHandle;

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
        type: InputTypes::Text,
        autocomplete: AllTimezoneIdentifiers::class,
        default: GetMemberTimeZone::class,
    )]
    public string $timezone = 'UTC';

    #[EventInput(
        type: InputTypes::Number,
        params: ['min' => 5, 'max' => 120],
        description: 'Length of meeting in minutes',
        suffix: 'Minutes',
    )]
    public int $duration = 30;

    #[EventInput(
        type: InputTypes::Select,
        options: ['No', 'Yes'],
    )]
    public bool $published = false;
    public ?Carbon $cancelled_at = null;

    #[EventInput(
        type: InputTypes::Select,
        options: InvitationLevels::class,
    )]
    public InvitationLevels $invitation_level = InvitationLevels::Free;

    #[EventInput(
        type: InputTypes::Number,
        description: 'If you set a capacity, members will be required to log in or register and RSVP',
    )]
    public ?int $capacity = null;

    #[EventInput(
        type: InputTypes::Url,
        description: 'Link where participants should go at the start of the gathering (Zoom, Google Meet, etc)',
    )]
    public ?string $url = null;

    public function applyToCalendarRangeState(CalendarRangeState $range): void
    {
        $end = $this->start->copy()->addMinutes($this->duration);
        $range->first_gathering_start = $range->first_gathering_start?->isBefore($this->start) ? $range->first_gathering_start : $this->start;
        $range->last_gathering_end = $range->last_gathering_end?->isAfter($end) ? $range->last_gathering_end : $end;
    }

    public function applyToGatheringState(GatheringState $gathering): void
    {
        $start = Carbon::parse($this->start->format('Y-m-d\TH:i'), $this->timezone)->setTimezone('UTC');


        $gathering->title = $this->title;
        $gathering->description = $this->description;
        $gathering->start = $start;
        $gathering->end = $start->copy()->addMinutes($this->duration);
        $gathering->published_at = $this->published ? now() : null;
        $gathering->cancelled_at = $this->cancelled_at;
        $gathering->invitation_level = $this->invitation_level;
        $gathering->capacity = $this->capacity;
    }

}
