@php use ArtisanBuild\Hallway\Messages\Events\MessageCreated;use ArtisanBuild\HallwayFlux\Livewire\ThreadComponent; @endphp
<div class="flex space-x-6">
    <div class="flex-grow max-w-7xl space-y-6">
        @forelse ($threads as $thread)
            <x-hallway-flux::thread :message="$thread"/>
        @empty
            <flux:heading size="xl">Nothing Here... Yet</flux:heading>
            <flux:subheading>
                <x-can :event="MessageCreated::class">Create the first message for {{ $channel->name }}</x-can>
            </flux:subheading>
        @endforelse
    </div>
    <div>
        <x-can :event="MessageCreated::class">
            <x-event-form-button :event="MessageCreated::class" :event_data="['channel_id' => $channel->id]"
                                 button_text="New Conversation" close="refreshChannel"/>
        </x-can>
    </div>

</div>
