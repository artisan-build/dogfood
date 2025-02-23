<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Pages\Events;

use ArtisanBuild\Adverbs\Traits\SimpleApply;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Pages\States\PageState;
use ArtisanBuild\VerbsFlux\Attributes\EventForm;
use ArtisanBuild\VerbsFlux\Attributes\EventInput;
use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[EventForm(
    submit_text: 'Create New Page',
    success: 'Page created!',
)]
class PageCreated extends Event
{
    use SimpleApply;

    public array $authorized_member_roles = [
        MemberRoles::Owner,
        MemberRoles::Admin,
    ];

    #[StateId(PageState::class)]
    public ?int $page_id = null;

    #[EventInput(
        type: InputTypes::Text,
    )]
    public string $title;

    #[EventInput(
        type: InputTypes::Checkbox,
    )]
    public bool $is_lobby = false;

    #[EventInput(
        type: InputTypes::Textarea,
    )]
    public ?string $free_content = null;

    #[EventInput(
        type: InputTypes::Textarea,
    )]
    public ?string $premium_content = null;
}
