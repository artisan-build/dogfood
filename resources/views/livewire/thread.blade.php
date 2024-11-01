@php use ArtisanBuild\Hallway\Messages\Events\CommentCreated; @endphp
<div class="space-y-8">
    <x-hallway-flux::message :message="$message"/>
    <div class="pl-24 space-y-6">
        @foreach ($message->comments as $comment)
            <x-hallway-flux::message :message="ArtisanBuild\Hallway\Messages\States\MessageState::load($comment['message_id'])"/>
        @endforeach
        <x-can :event="CommentCreated::class">
                <livewire:event-form :event="\ArtisanBuild\Hallway\Messages\Events\CommentCreated::class"
                                     :event_data="['thread_id' => $message->id]" @saved="$refresh"/>
            </x-can>
    </div>



</div>
