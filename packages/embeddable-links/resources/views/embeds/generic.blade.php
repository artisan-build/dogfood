<a
    href="{{ $url }}"
    target="{{ $options['target'] ?? '_blank' }}"
    rel="noopener noreferrer"
    class="block no-underline hover:opacity-90 transition-opacity"
>
    <flux:card class="embeddable-link-card cursor-pointer">
        <div class="p-4">
            <flux:heading size="lg" class="mb-2">
                {{ $metadata['title'] ?? 'Untitled Page' }}
            </flux:heading>

            @if(isset($metadata['description']))
                <flux:text class="text-gray-600 dark:text-gray-400 mb-3">
                    {{ $metadata['description'] }}
                </flux:text>
            @endif

            <flux:text size="sm" class="text-gray-500 dark:text-gray-500">
                {{ $url }}
            </flux:text>
        </div>
    </flux:card>
</a>
