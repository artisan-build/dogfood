@props(['message'])

<div class="text-right">
    <flux:button href="{{route(config('hallway-flux.route-name-prefix').'thread', ['message' => $message])}}" icon="chat-bubble-oval-left-ellipsis" size="xs">{{ count($message->comments) }} {{ str('Comment')->plural(count($message->comments)) }}</flux:button>
</div>

