<x-app-layout>
    <div class="text-center content-center mx-auto space-y-6">
        <flux:heading level="1" class="!text-2xl text-center inline-flex" x-data="{ showUp: false, hideEr: false }"
                      x-init="setTimeout(() => hideEr = true, 2000)">
            Your SaaS Start
            <span class="inline-block w-[2.1ch] text-left">
        <span
            x-show="!hideEr"
            x-transition:leave.duration.500ms
            @transitionend="showUp = true"
            class="absolute">er Kit
        </span>
        <span
            x-cloak
            x-show="showUp"
            x-transition:enter.duration.500ms
            class="absolute text-[var(--color-accent)]">up Kit
        </span>
        </span>
        </flux:heading>

        <flux:subheading size="xl">Artisan Build makes it easy to go from idea to MVP in a weekend.</flux:subheading>

        <iframe class="mx-auto" width="560" height="315"
                src="https://www.youtube.com/embed/RhVPFY86O4I?si=MQkf8pFBrz9anpIi" title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>

    <section name="features" class="py-24 sm:py-32">
        <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
            <x-artisan-ui::bentos.grid-six-column>
                <x-artisan-ui::bentos.cell-four-column>

                </x-artisan-ui::bentos.cell-four-column>
                <x-artisan-ui::bentos.cell-two-column></x-artisan-ui::bentos.cell-two-column>
                <x-artisan-ui::bentos.cell-two-column></x-artisan-ui::bentos.cell-two-column>
                <x-artisan-ui::bentos.cell-four-column></x-artisan-ui::bentos.cell-four-column>
            </x-artisan-ui::bentos.grid-six-column>
        </div>
    </section>

    <section name="pricing">
        <livewire:till:pricing-section/>
    </section>

    <section name="email-signup" class="my-24">
        <livewire:marketing:email-subscription-form
            heading="Get Notified When We Launch"
            subheading="We will be opening early access very soon."
            icon="envelope"
        />
    </section>


</x-app-layout>
