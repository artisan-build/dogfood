@props(['plan', 'display'])
<div class="relative">
    <x-artisan-ui::card :highlighted="$plan->current" class="relative flex min-h-96 h-full flex-col overflow-hidden">
        <flux:heading size="lg" class="min-h-12">
            {{ $plan->heading }} {{ $plan->current }}
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
        <flux:text class="!text-5xl font-bold my-4">
            {{ $plan->currency->format($plan->prices[$display]) }}
            <span class="!text-xl">
                            @if ($display === 'life')
                    Forever
                @else
                    / {{ str($display)->headline() }}
                @endif
                        </span>
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
