<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Messages\Events;

use ArtisanBuild\Hallway\Channels\Enums\ChannelPermissionTypes;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberState;
use ArtisanBuild\Hallway\Messages\States\MessageState;
use ArtisanBuild\VerbsFlux\Attributes\EventForm;
use ArtisanBuild\VerbsFlux\Attributes\EventInput;
use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use Illuminate\Support\Facades\Context;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[EventForm()]
class MessageCreated extends Event
{
    use AuthorizesBasedOnMemberState;

    public ChannelPermissionTypes $needs_channel_permissions = ChannelPermissionTypes::Comment;

    #[StateId(MessageState::class)]
    public ?int $message_id = null;

    #[StateId(ChannelState::class)]
    public int $channel_id;

    #[EventInput(
        type: InputTypes::Textarea,
        label: 'Your Message',
    )]
    public string $content;

    public function applyToMessageState(MessageState $state): void
    {
        $state->channel_id = $this->channel_id;
        $state->member_id = Context::get('active_member')->id;
        $state->content = $this->content;
    }

    public function applyToChannelState(ChannelState $state): void
    {
        $state->message_ids[] = $this->message_id;
    }
}
