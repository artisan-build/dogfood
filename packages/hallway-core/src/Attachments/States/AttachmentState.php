<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Attachments\States;

use ArtisanBuild\Hallway\Attachments\Enums\AttachmentDisplayTemplates;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Messages\States\MessageState;
use Thunk\Verbs\State;

class AttachmentState extends State
{
    public int $message_id;

    public string $url;

    public AttachmentDisplayTemplates $template = AttachmentDisplayTemplates::Image;

    public function message(): MessageState
    {
        return MessageState::load($this->message_id);
    }

    public function channel(): ChannelState
    {
        return ChannelState::load($this->message()->channel_id);
    }
}
