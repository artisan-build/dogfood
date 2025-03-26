@props(['level' => '3'])
<flux:heading level="{{ $level }}" size="xl" class="-ml-4 pl-4 border-l-2 border-[var(--color-accent)]">
    {{ $slot }}
</flux:heading>
