<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\ChannelMembership\Models;

use ArtisanBuild\Adverbs\Traits\GetsRowsFromVerbsStates;
use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\ChannelMembership\Traits\BelongsToMembersAndChannels;
use ArtisanBuild\Hallway\Members\States\ChannelMembershipState;
use Illuminate\Database\Eloquent\Model;

class ChannelMembership extends Model
{
    use BelongsToMembersAndChannels;
    use GetsRowsFromVerbsStates;
    use HasVerbsState;

    protected string $stateClass = ChannelMembershipState::class;




}
