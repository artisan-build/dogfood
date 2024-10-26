<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Messages\Events;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Messages\States\MessageState;
use ArtisanBuild\VerbsFlux\Attributes\EventForm;
use ArtisanBuild\VerbsFlux\Attributes\EventInput;
use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[EventForm()]
abstract class MessageCreated extends Event
{
    #[StateId(MessageState::class)]
    public ?int $message_id = null;

    #[StateId(ChannelState::class)]
    public int $channel_id;

    #[StateId(MessageState::class)]
    public ?int $thread_id = null;

    #[EventInput(
        type: InputTypes::Textarea,
        label: 'Your Message',
    )]
    public string $content;
}
