<flux:navlist.group expandable="true" heading="Channels" class="hidden lg:grid">
    @foreach ($channels as $channel)
        <flux:navlist.item  wire:navigate="true" href="{{ route(config('hallway-flux.route-name-prefix') . 'channel', ['channel' => $channel]) }}" icon="lock-open">{{ $channel->name }}</flux:navlist.item>
    @endforeach
</flux:navlist.group>
