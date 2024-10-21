<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Models;

use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Channel extends Model
{
    use HasVerbsState;

    protected string $stateClass = ChannelState::class;

    public function casts()
    {
        return [
            'member_ids' => 'array',
        ];
    }
}
