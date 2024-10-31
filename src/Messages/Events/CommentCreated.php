<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Messages\Events;

use ArtisanBuild\Hallway\Channels\Enums\ChannelPermissionTypes;
use ArtisanBuild\Hallway\Messages\States\MessageState;
use ArtisanBuild\VerbsFlux\Attributes\EventForm;
use ArtisanBuild\VerbsFlux\Attributes\EventInput;
use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use Illuminate\Support\Facades\Context;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[EventForm(submit_text: 'Comment')]
class CommentCreated extends Event
{
    public ChannelPermissionTypes $needs_channel_permissions = ChannelPermissionTypes::Comment;

    #[StateId(MessageState::class)]
    public ?int $message_id = null;

    #[StateId(MessageState::class)]
    public int $thread_id;

    #[EventInput(
        type: InputTypes::Textarea,
        label: 'Comment',
        placeholder: 'Comment...',
    )]
    public string $content;

    public function applyToMessageState(MessageState $message): void
    {
        $message->channel_id = MessageState::load($this->thread_id)->channel_id;
        $message->member_id = Context::get('active_member')->id;
        $message->content = $this->content;
    }

    public function applyToThreadState(MessageState $thread): void
    {
        $thread->comments[] = [
            'message_id' => $this->message_id,
            'member_id' => Context::get('active_member')->id,
        ];
    }
}
