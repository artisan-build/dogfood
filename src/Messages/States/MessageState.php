<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Messages\States;

use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Moderation\Enums\ModerationMessageStates;
use Carbon\Carbon;
use Thunk\Verbs\State;

class MessageState extends State
{
    public int $channel_id;
    public int $member_id;

    public ModerationMessageStates $moderation_state = ModerationMessageStates::None;
    public ?int $thread_id = null;

    public string $content;

    public ?Carbon $pinned_at = null;
    public ?int $pinned_by_id = null;

    public array $comments = [];
    public array $revisions = [];
    public array $mentions = [];

    public function member(): MemberState
    {
        return MemberState::load($this->member_id);
    }

    public function pinned_by(): ?MemberState
    {
        return null === $this->pinned_by_id ? null : MemberState::load($this->pinned_by_id);
    }

}
