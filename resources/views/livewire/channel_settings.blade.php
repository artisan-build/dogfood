@use('ArtisanBuild\Hallway\Channels\Events\CommunityChannelUpdated;')
<div>
    <hallway-can :event="CommunityChannelUpdated::class">
        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $channel->name }} Settings</flux:heading>
                <flux:subheading>You can change your channel's name and type here:</flux:subheading>
            </div>
            <livewire:event-form :event="CommunityChannelUpdated::class"
                                 :state="$channel->verbs_state()"/>
        </flux:card>
    </hallway-can>

</div>
