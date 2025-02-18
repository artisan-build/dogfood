<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Models;

use ArtisanBuild\Adverbs\Traits\GetsRowsFromVerbsStates;
use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use GetsRowsFromVerbsStates;
    use HasVerbsState;

    protected string $stateClass = ChannelState::class;

    protected $guarded = [];

    public function flux_url(): Attribute
    {
        return Attribute::make(
            get: fn () => route(config('hallway-flux.route-name-prefix').'channel', ['channel' => $this]),
        );
    }

    public function casts()
    {
        return [
            'member_ids' => 'array',
        ];
    }
}
