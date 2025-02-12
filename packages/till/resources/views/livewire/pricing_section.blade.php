@php use ArtisanBuild\Till\Enums\PlanTerms; @endphp
<flux:card>
    <div class="max-w-lg mx-auto">
        <flux:radio.group wire:model.live="display" variant="segmented">
            <flux:radio :class="$displays['month'] ? '' : 'hidden'" value="month" label="Monthly"/>
            <flux:radio :class="$displays['year'] ? '' : 'hidden'" value="year" label="Annual"/>
            @if (!config('till.always_show_lifetime'))
            <flux:radio :class="$displays['life'] ? '' : 'hidden'" value="life" label="Lifetime"/>
            @endif
        </flux:radio.group>
    </div>

    <div class="mt-10 grid grid-cols-1 gap-4 sm:mt-16 lg:grid-cols-3">
        @foreach ($plans as $plan)
            @if ($plan->prices[$display] !== null)
                <x-till::price-card :plan="$plan" :display="$display"/>
            @endif
        @endforeach
        @if (config('till.always_show_lifetime'))
            @foreach ($plans as $plan)
                @if ($plan->prices['life'] !== null)
                    <x-till::price-card :plan="$plan" :display="PlanTerms::Life->value"/>
                @endif
            @endforeach
        @endif
    </div>

</flux:card>
