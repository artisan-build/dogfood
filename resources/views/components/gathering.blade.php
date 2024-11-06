@php use ArtisanBuild\HallwayFlux\Livewire\RsvpComponent; @endphp
@props(['gathering'])
<flux:card :class="$gathering->end->isPast() ? 'opacity-50 space-y-6' : 'space-y-6'">
    <div>
        <flux:heading size="xl">{{ $gathering->title }}
            <flux:badge class="float-right">{{ $gathering->start->diffInMinutes($gathering->end) }}
                Minutes
            </flux:badge>
        </flux:heading>
        <flux:subheading>{{ $gathering->verbs_state()->forMember(\Illuminate\Support\Facades\Context::get('active_member'))->start->format('l F jS Y h:i A') }}</flux:subheading>
    </div>

    <div class="markdown_body">{!! $gathering->description !!}</div>

    @livewire(RsvpComponent::class, ['gathering' => $gathering])
</flux:card>
