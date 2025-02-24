@php use ArtisanBuild\Hallway\Messages\Events\MessageCreated;use ArtisanBuild\HallwayFlux\Livewire\NewMessageComponent;use ArtisanBuild\HallwayFlux\Livewire\ThreadComponent; @endphp
<div class="max-w-5xl flex flex-col" wire:poll.10s x-data>
    <div id="messageContainer" x-ref="messageContainer" class="grow space-y-6">
        @forelse ($threads as $thread)
            <x-hallway-flux::thread :message="$thread"/>
        @empty
            <flux:heading size="xl">Nothing Here... Yet</flux:heading>
            <flux:subheading>
                <x-can :event="MessageCreated::class">Create the first message for {{ $channel->name }}</x-can>
            </flux:subheading>
        @endforelse
    </div>
    <x-slot name="footer">
        <x-can :event="MessageCreated::class">
            @livewire(NewMessageComponent::class)
        </x-can>
    </x-slot>

</div>
