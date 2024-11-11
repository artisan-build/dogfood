<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Events;

use ArtisanBuild\Adverbs\Traits\SimpleApply;
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
    submit_text: 'Update Gathering',
)]
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

    #[EventInput(
        type: InputTypes::Text,
    )]
    public ?string $title = null;

    #[EventInput(
        type: InputTypes::Textarea,
    )]
    public ?string $description = null;


    #[EventInput(
        type: InputTypes::DatetimeLocal,
    )]
    public ?Carbon $start = null;

    #[EventInput(
        type: InputTypes::DatetimeLocal,
    )]
    public ?Carbon $end = null;

    #[EventInput(
        type: InputTypes::DatetimeLocal,
    )]
    public ?Carbon $published_at = null;

    #[EventInput(
        type: InputTypes::DatetimeLocal,
    )]
    public ?Carbon $cancelled_at = null;

    #[EventInput(
        type: InputTypes::Select,
        options: InvitationLevels::class,
    )]
    public ?InvitationLevels $invitation_level = null;

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

}
