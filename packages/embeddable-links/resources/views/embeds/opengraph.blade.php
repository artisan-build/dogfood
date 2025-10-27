<flux:card class="embeddable-link-card">
    @if(isset($metadata['image']))
        <img
            src="{{ $metadata['image'] }}"
            alt="{{ $metadata['title'] ?? 'Link preview' }}"
            class="w-full h-48 object-cover rounded-t-lg"
        />
    @endif

    <div class="p-4">
        <flux:heading size="lg" class="mb-2">
            {{ $metadata['title'] ?? 'Untitled' }}
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
                {{ parse_url($url, PHP_URL_HOST) }}
            </a>
        </flux:text>
    </div>
</flux:card>
