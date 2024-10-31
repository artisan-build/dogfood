@php use ArtisanBuild\Hallway\Messages\Events\CommentCreated;use ArtisanBuild\Hallway\Messages\States\MessageState; @endphp
@props(['message'])
<div>
    <flux:modal.trigger :name="'thread-'.$message->id">
        <div class="hover:bg-gray-50 p-4">

            <x-hallway-flux::message :message="$message"/>
            <x-hallway-flux::reply :message="$message"/>
        </div>
    </flux:modal.trigger>
    <flux:modal :name="'thread-'.$message->id" variant="flyout" class="space-y-6">
        <x-hallway-flux::message :message="$message"/>
        <div class="pl-6 space-y-6">
            @foreach ($message->comments as $comment)
                <x-hallway-flux::message :message="MessageState::load($comment['message_id'])"/>
            @endforeach
        </div>


        <x-can :event="CommentCreated::class">
            <livewire:event-form :event="\ArtisanBuild\Hallway\Messages\Events\CommentCreated::class"
                                 :event_data="['thread_id' => $message->id]"/>
        </x-can>
    </flux:modal>
</div>

