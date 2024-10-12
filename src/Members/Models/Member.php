<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Models;

use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\ChannelMembership\Models\ChannelMembership;
use ArtisanBuild\Hallway\Channels\Models\Channel;
use ArtisanBuild\Hallway\Members\States\MemberState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Sushi\Sushi;

class Member extends Model
{
    use HasVerbsState;
    use Sushi;

    protected string $stateClass = MemberState::class;

    public function getRows(): array
    {
        return $this->loadStatesIntoSushi();
    }

    public function channel_memberships(): HasMany
    {
        return $this->hasMany(ChannelMembership::class);
    }

    public function channels(): HasManyThrough
    {
        return $this->hasManyThrough(Channel::class, ChannelMembership::class);
    }

}
