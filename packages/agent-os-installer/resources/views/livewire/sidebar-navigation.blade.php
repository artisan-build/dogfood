<nav class="space-y-1">
    @foreach ($this->navigationItems as $item)
        @if ($item['type'] === 'heading')
            <div class="pt-4 pb-2 px-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                {{ $item['label'] }}
            </div>
        @else
            <a
                href="{{ route('agent-os.view', ['path' => $item['path']]) }}"
                @class([
                    'block px-3 py-2 rounded-md text-sm font-medium transition-colors',
                    'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100' => $item['active'],
                    'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' => ! $item['active'],
                ])
                wire:navigate
            >
                @if ($item['type'] === 'spec' && isset($item['date']))
                    <div class="flex items-baseline gap-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item['date'] }}</span>
                        <span>{{ $item['label'] }}</span>
                    </div>
                @else
                    {{ $item['label'] }}
                @endif
            </a>
        @endif
    @endforeach
</nav>
