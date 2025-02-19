<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\ChannelMembership\Traits;

use ArtisanBuild\Hallway\Channels\Models\Channel;
use ArtisanBuild\Hallway\Members\Models\Member;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToMembersAndChannels
{
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
