<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\States;

use App\States\UserState;
use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use Illuminate\Support\Collection;
use Thunk\Verbs\State;

class ChannelState extends State
{
    public string $name;
    public ChannelTypes $type;

    public array $member_ids = [];

    public function members(): Collection
    {
        return collect($this->member_ids)->map(fn(int $id) => UserState::load($id));
    }
}
