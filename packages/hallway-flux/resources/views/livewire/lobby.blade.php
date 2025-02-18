@php use ArtisanBuild\Hallway\Pages\Events\PageUpdated; @endphp
<div>
    <x-hallway::can :event="PageUpdated::class">
        <div class="float-right">
            <flux:modal.trigger name="edit-page">
                <flux:button>Edit This Page</flux:button>
            </flux:modal.trigger>

            <flux:modal name="edit-page" variant="flyout" class="space-y-6">
                <livewire:event-form :event="\ArtisanBuild\Hallway\Pages\Events\PageUpdated::class"
                                     :state="$lobby->verbs_state()"/>
            </flux:modal>
        </div>
    </x-hallway::can>

    <x-markdown :content="data_get($lobby, 'free_content')"/>
</div>
