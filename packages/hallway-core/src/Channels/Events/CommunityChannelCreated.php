<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Events;

use ArtisanBuild\Adverbs\Traits\ReturnsModelInstanceOnHandle;
use ArtisanBuild\Adverbs\Traits\SimpleApply;
use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberState;
use ArtisanBuild\VerbsFlux\Attributes\EventForm;
use ArtisanBuild\VerbsFlux\Attributes\EventInput;
use ArtisanBuild\VerbsFlux\Contracts\RedirectsOnSuccess;
use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use Throwable;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[EventForm(
    submit_text: 'Create Community Channel',
    on_success: RedirectsOnSuccess::class,
)]
class CommunityChannelCreated extends Event
{
    use AuthorizesBasedOnMemberState;
    use ReturnsModelInstanceOnHandle;
    use SimpleApply;

    public array $authorized_member_roles = [
        MemberRoles::Owner,
        MemberRoles::Admin,
    ];

    #[StateId(ChannelState::class)]
    public ?int $channel_id = null;

    #[EventInput(
        type: InputTypes::Text,
    )]
    public string $name;

    #[EventInput(
        type: InputTypes::Text,
        params: ['maxlength' => '128'],
        rules: ['string', 'required', 'max:128'],
    )]
    public string $description = '';

    #[EventInput(
        type: InputTypes::Select,
        options: ChannelTypes::class,
        options_filter: 'isCommunityChannel',
    )]
    public ChannelTypes $type;

    /**
     * @throws Throwable
     */
    public function validate(): void
    {
        $this->assert($this->type->isCommunityChannel());
    }
}
