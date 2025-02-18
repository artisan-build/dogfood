@php use ArtisanBuild\Hallway\Calendar\Events\GatheringUpdated;use ArtisanBuild\HallwayFlux\Livewire\RsvpComponent; @endphp
@props(['gathering'])
<flux:card :class="$gathering->end->isPast() ? 'opacity-50 space-y-6' : 'space-y-6'">
    <div>
        <flux:heading size="xl">{{ $gathering->title }}
            <flux:badge class="float-right">{{ $gathering->start->diffInMinutes($gathering->end) }}
                Minutes
            </flux:badge>
        </flux:heading>
        <flux:subheading>{{ $gathering->forMember(\Illuminate\Support\Facades\Context::get('active_member'))->start->format('l F jS Y h:i A') }} <span class="float-right">{{ Session::get('timezone', 'UTC') }}</span></flux:subheading>
    </div>

    <div class="markdown_body">{!! $gathering->description !!}</div>

    @livewire(RsvpComponent::class, ['gathering' => $gathering])

    @if ($gathering->url)
        <x-markdown :content='"\n".$gathering->url'/>
    @endif
    <x-hallway::can :event="GatheringUpdated::class">
        <x-event-form-button :event="GatheringUpdated::class" :state="$gathering" button_text="Edit Gathering"/>
    </x-hallway::can>
</flux:card>
