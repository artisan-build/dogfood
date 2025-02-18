<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Attachments\Events;

use ArtisanBuild\Hallway\Attachments\States\AttachmentState;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Messages\States\MessageState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class FileAttachedToMessage extends Event
{
    #[StateId(MessageState::class)]
    public int $message_id;

    #[StateId(AttachmentState::class)]
    public int $attachment_id;

    #[StateId(MemberState::class)]
    public int $member_id;

    public string $url;

    public function applyToMemberState(MemberState $member): void
    {
        $member->attachment_ids[] = $this->attachment_id;
    }

    public function applyToMessageState(MessageState $message): void
    {
        $message->attachment_ids[] = $this->attachment_id;
        ChannelState::load($message->channel_id)->attachment_ids[] = $this->attachment_id;
    }

    public function applyToAttachmentState(AttachmentState $attachment): void
    {
        $attachment->message_id = $this->message_id;
        $attachment->url = $this->url;
    }
}
