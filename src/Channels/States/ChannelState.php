<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\States;

use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Members\States\MemberState;
use Illuminate\Support\Collection;
use Thunk\Verbs\State;

class ChannelState extends State
{
    public string $name;
    public ChannelTypes $type;
    public ?int $owner_id = null;

    public array $member_ids = [];

    public function members(): Collection
    {
        return collect($this->member_ids)->map(fn(int $id) => MemberState::load($id));
    }
}
