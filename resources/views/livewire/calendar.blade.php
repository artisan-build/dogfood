@php use ArtisanBuild\Hallway\Calendar\Events\GatheringCreated; @endphp
<div>
    <div class="flex space-x-4">
        <div class="flex-grow">
            @if (\Illuminate\Support\Facades\Context::get('active_member')->can(GatheringCreated::class))
                <div class="float-right">
                    <flux:modal.trigger name="add-gathering">
                        <flux:button>Add Gathering</flux:button>
                    </flux:modal.trigger>
                </div>
            @endif

        </div>
        <div class="flex-0">
            @foreach ($months as $key => $month)
                @if ($range === '' || $range === $key)
                    <flux:card>
                        <flux:heading class="mb-4" size="lg">
                            {{ data_get($month, 'title') }}
                            <flux:button.group class="float-right">
                                <flux:button wire:navigate
                                             href="{{ $months->has(data_get($month, 'previous')) ? route(config('hallway-flux.route-name-prefix') . 'calendar', ['range' => data_get($month, 'previous')]) : url()->current()}}"
                                             size="xs" icon="chevron-double-left" square="true"
                                             variant="{{ $months->has(data_get($month, 'previous')) ? 'outline' : 'filled' }}"
                                             class="{{ $months->has(data_get($month, 'previous')) ? '' : 'cursor-not-allowed !text-zinc-400' }}"></flux:button>
                                <flux:button wire:navigate
                                             href="{{ $months->has(data_get($month, 'next')) ? route(config('hallway-flux.route-name-prefix') . 'calendar', ['range' => data_get($month, 'next')]) : url()->current() }}"
                                             size="xs" icon="chevron-double-right" square="true"></flux:button>
                            </flux:button.group>
                        </flux:heading>
                        @foreach (data_get($month, 'weeks') as $week)
                            <flux:button.group>
                                @foreach ($week as $day)
                                    <flux:button
                                        class="rounded-none {{ random_int(1, 5) !== 3 ? '!text-zinc-400' : '' }}"
                                        square="true"
                                        variant="{{ data_get($day, 'today') ? 'filled' : 'outline' }}">{{ data_get($day, 'number') }}</flux:button>
                                @endforeach
                            </flux:button.group>
                        @endforeach
                    </flux:card>
                @endif

            @endforeach
        </div>
    </div>


    <flux:modal name="add-gathering" variant="flyout" class="space-y-6">
        <livewire:event-form :event="\ArtisanBuild\Hallway\Calendar\Events\GatheringCreated::class"/>
    </flux:modal>
</div>
