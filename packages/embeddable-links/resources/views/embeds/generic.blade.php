<flux:card class="embeddable-link-card">
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
            <a
                href="{{ $url }}"
                target="_blank"
                rel="noopener noreferrer"
                class="hover:underline"
            >
                {{ $url }}
            </a>
        </flux:text>
    </div>
</flux:card>
