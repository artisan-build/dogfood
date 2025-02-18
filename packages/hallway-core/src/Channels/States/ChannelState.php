<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\States;

use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Messages\States\MessageState;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Context;
use Thunk\Verbs\State;

class ChannelState extends State
{
    public string $name;
    public string $description = '';
    public ChannelTypes $type;
    public ?int $owner_id = null;

    public array $member_ids = [];

    public array $message_ids = [];

    public array $attachment_ids = [];

    public function members(): Collection
    {
        return collect($this->member_ids)->map(fn(int $id) => MemberState::load($id));
    }

    public function messages(): Collection
    {
        return collect($this->message_ids)->map(fn(int $id) => MessageState::load($id));
    }

    public function availableToMember(): bool
    {
        // All open channels are available to all members
        if ($this->type->isOpenChannel()) {
            return true;
        }

        $member = Context::get('active_member');

        if (in_array($member->role, [
            MemberRoles::Owner,
            MemberRoles::Admin,
            MemberRoles::Moderator,
            MemberRoles::ModeratorBot,
        ], true)) {
            return true;
        }

        // Private channels and member channels are only available to those that are in them (even if muted)
        return in_array($this->id, $member?->channel_ids, true);

    }

    public function inChannel(): bool
    {
        return in_array(Context::get('active_member')?->id, $this->member_ids, true);
    }
}
