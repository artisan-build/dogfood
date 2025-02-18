<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Traits;

use ArtisanBuild\Hallway\ChannelMembership\Models\ChannelMembership;
use ArtisanBuild\Hallway\Channels\Models\Channel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait HasChannelMemberships
{
    public function channel_memberships(): HasMany
    {
        return $this->hasMany(ChannelMembership::class);
    }

    public function channels(): HasManyThrough
    {
        return $this->hasManyThrough(Channel::class, ChannelMembership::class);
    }
}
