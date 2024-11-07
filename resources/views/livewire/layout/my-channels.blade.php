@php use ArtisanBuild\Hallway\Channels\Events\CommunityChannelCreated;use ArtisanBuild\HallwayFlux\Livewire\ChannelsComponent; @endphp
<div>
    <flux:navlist.group expandable="true" heading="Channels" class="hidden lg:grid">
        @foreach ($channels as $channel)
            @if ($channel->inChannel())
                <flux:navlist.item wire:navigate="true"
                                   href="{{ route(config('hallway-flux.route-name-prefix') . 'channel', ['channel' => $channel]) }}"
                                   icon="lock-open">{{ $channel->name }}</flux:navlist.item>
            @endif
        @endforeach
        <flux:navlist.item wire:navigate="true"
                           href="{{ route(config('hallway-flux.route-name-prefix') . 'channels') }}" icon="queue-list"
                           :badge="$available">All Channels
        </flux:navlist.item>
        @if (\Illuminate\Support\Facades\Context::get('active_member')?->can(CommunityChannelCreated::class))
            <flux:modal.trigger name="add-community-channel">
                <flux:navlist.item icon="plus">Add Community Channel</flux:navlist.item>
            </flux:modal.trigger>

            <flux:modal variant="flyout" name="add-community-channel" class="md:w-96 space-y-6">
                <div>
                    <flux:heading size="lg">Add Community Channel</flux:heading>
                    <flux:subheading>Create a new channel for your community.</flux:subheading>
                </div>

                <livewire:event-form :event="\ArtisanBuild\Hallway\Channels\Events\CommunityChannelCreated::class"/>
            </flux:modal>
        @endif
    </flux:navlist.group>


    <flux:modal wire:model.self="list_channels" variant="flyout">
        <flux:heading>Join Some Channels</flux:heading>
        <x-flux::subheading>Here are the channels that are available to you. Please join at least one to get started.
        </x-flux::subheading>
        @livewire(ChannelsComponent::class)
    </flux:modal>
</div>

