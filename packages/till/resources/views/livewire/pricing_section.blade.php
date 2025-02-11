<flux:card>
    <div class="max-w-lg mx-auto">
        <flux:radio.group wire:model.live="display" variant="segmented">
            <flux:radio :class="$displays['month'] ? '' : 'hidden'" value="month" label="Monthly" />
            <flux:radio :class="$displays['year'] ? '' : 'hidden'" value="year" label="Annual" />
            <flux:radio :class="$displays['life'] ? '' : 'hidden'" value="life" label="Lifetime" />
        </flux:radio.group>
    </div>
    <div class="mt-10 grid grid-cols-1 gap-4 sm:mt-16 lg:grid-cols-3">
        @foreach ($plans as $plan)
            <div class="relative">
                <x-artisan-ui::card :highlighted="$plan->current" class="relative flex min-h-96 h-full flex-col overflow-hidden">
                    <flux:heading size="lg" class="min-h-12">
                        {{ $plan->heading }}
                        @if ($plan->badge['text'] !== '')
                            <flux:badge
                                    :size="$plan->badge['size']"
                                    :color="$plan->badge['color']"
                                    :variant="$plan->badge['variant']"
                                    :icon="$plan->badge['icon']"
                                    class="float-right">{{ $plan->badge['text'] }}</flux:badge>
                        @endif
                    </flux:heading>
                    <flux:subheading>{{ $plan->subheading }}</flux:subheading>
                    <flux:text class="text-4xl font-bold my-4">
                        {{ $plan->currency->format($plan->prices[$display]['price']) }}
                    </flux:text>
                    <flux:navlist>
                        @foreach ($plan->features as $feature)
                            <flux:navlist.item class="cursor-default" :icon="$feature['icon']">
                                {{ $feature['text'] }}
                            </flux:navlist.item>
                        @endforeach
                    </flux:navlist>

                    <div class="flex-1"></div> <!-- Spacer to push button down -->
                    @if ($plan->current)
                        <flux:button disabled>Current Plan</flux:button>
                    @else
                    <flux:button :href="$plan->subscribeUrl($display)" variant="primary">Enroll Now</flux:button>
                    @endif
                </x-artisan-ui::card>
            </div>
        @endforeach
    </div>

</flux:card>
