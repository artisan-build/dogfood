<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\ChannelMembership\Events\MemberJoinedChannel;
use ArtisanBuild\Hallway\ChannelMembership\Events\MemberLeftChannel;
use ArtisanBuild\Hallway\Channels\Events\ChannelsRequested;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Context;
use Livewire\Component;

class ChannelsComponent extends Component
{
    public Collection $channels;

    public array $membership = [];

    public function mount(): void
    {
        $this->channels = ChannelsRequested::commit();

        $this->membership = $this->channels->mapWithKeys(fn ($channel) => [$channel->id => $channel->inChannel()])->toArray();
    }

    public function updatingMembership($value, $key): void
    {
        $this->membership[$key] = $value;

        $value
            ? MemberJoinedChannel::commit(channel_id: (int) $key, member_id: Context::get('active_member')->id)
            : MemberLeftChannel::commit(channel_id: (int) $key, member_id: Context::get('active_member')->id);
    }

    public function render()
    {
        return view('hallway-flux::livewire.channels')->layout('hallway-flux::layouts.app');
    }
}
