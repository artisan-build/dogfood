<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire\Layout;

use ArtisanBuild\Hallway\Channels\Events\ChannelsRequested;
use Illuminate\Support\Facades\Context;
use Livewire\Component;

class MyChannelsComponent extends Component
{
    public $channels;

    public int $available;

    public int $in = 0;

    public bool $list_channels = false;

    public function mount(): void
    {
        $this->channels = ChannelsRequested::commit();

        $this->available = $this->channels->filter(fn ($channel) => $channel->availableToMember())->count();

        $member = Context::get('active_member');
        $this->in = count(array_diff($member->channel_ids, $member->muted_channel_ids));

        if ($this->in === 0) {
            $this->list_channels = true;
        }
    }

    public function render()
    {
        return view('hallway-flux::livewire.layout.my-channels');
    }
}
