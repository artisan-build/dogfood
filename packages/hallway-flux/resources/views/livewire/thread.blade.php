@php use ArtisanBuild\Hallway\Messages\Events\CommentCreated;use ArtisanBuild\Hallway\Messages\Events\MessageCreated;use ArtisanBuild\HallwayFlux\Livewire\NewMessageComponent; @endphp
<div class="space-y-8" wire:poll.10s>
    <x-hallway-flux::message :message="$message" :preview="false"/>
    <div class="pl-24 space-y-6">
        @foreach ($message->comments as $comment)
            <x-hallway-flux::message
                :message="ArtisanBuild\Hallway\Messages\States\MessageState::load($comment['message_id'])"/>
        @endforeach
        <x-slot name="footer">
            <x-can :event="CommentCreated::class">
                <x-can :event="MessageCreated::class">
                    @livewire(NewMessageComponent::class)
                </x-can>
            </x-can>
        </x-slot>

    </div>


</div>
