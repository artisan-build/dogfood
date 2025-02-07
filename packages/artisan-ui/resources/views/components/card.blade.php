@props(['highlighted' => false])
@php
$border = $highlighted
    ? 'border-[var(--color-accent)]'
    : 'border-zinc-200 dark:border-white/10';

$classes = "bg-white dark:bg-white/10 p-6 rounded-xl border {{ $border }}";
@endphp
<div {{ $attributes->class($classes) }}>
    {{ $slot }}
</div>
