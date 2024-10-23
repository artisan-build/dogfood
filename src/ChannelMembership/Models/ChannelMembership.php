<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\ChannelMembership\Models;

use ArtisanBuild\Adverbs\Traits\GetsRowsFromVerbsStates;
use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\Channels\Models\Channel;
use ArtisanBuild\Hallway\Members\Models\Member;
use ArtisanBuild\Hallway\Members\States\ChannelMembershipState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelMembership extends Model
{
    use GetsRowsFromVerbsStates;
    use HasVerbsState;

    protected string $stateClass = ChannelMembershipState::class;


    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

}
