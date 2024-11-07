<flux:table class="p-4">
    <flux:columns>
        <flux:column>Name</flux:column>
        <flux:column>Members</flux:column>
        <flux:column>In Channel</flux:column>
        <flux:column>Notifications</flux:column>
    </flux:columns>

    <flux:rows>
        @foreach ($channels as $channel)
            @if ($channel->availableToMember())
            <flux:row>
                <flux:cell>
                    <flux:tooltip :content="$channel->description">
                        <flux:button class="underline decoration-dashed" variant="ghost">{{ $channel->name }}</flux:button>
                    </flux:tooltip>
                </flux:cell>
                <flux:cell>{{ count($channel->member_ids) }}</flux:cell>
                <flux:cell>
                    <flux:switch wire:model.live="membership.{{$channel->id}}"/>
                </flux:cell>
                <flux:cell class="space-x-6">
                    Coming Soon
                </flux:cell>
            </flux:row>
            @endif
        @endforeach
    </flux:rows>
</flux:table>
