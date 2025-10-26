@props(['message', 'index'])

<div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}"
     wire:key="message-{{ $index }}">
    <div class="group relative max-w-[70%] rounded-lg p-4 {{ $message['role'] === 'user'
        ? 'bg-blue-600 text-white'
        : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700' }}
        {{ isset($message['reverted']) && $message['reverted'] ? 'opacity-50' : '' }}">

        {{-- Message Header with Role and Timestamp --}}
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <div class="text-xs font-semibold {{ $message['role'] === 'user' ? 'text-blue-200' : 'text-gray-500 dark:text-gray-400' }}">
                    {{ $message['role'] === 'user' ? 'You' : 'Assistant' }}
                </div>
                @if (isset($message['reverted']) && $message['reverted'])
                    <span class="text-xs px-2 py-0.5 rounded bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300">
                        Reverted
                    </span>
                @endif
            </div>
            @if (isset($message['timestamp']))
                <div class="text-xs {{ $message['role'] === 'user' ? 'text-blue-300' : 'text-gray-400 dark:text-gray-500' }}">
                    {{ \Carbon\Carbon::parse($message['timestamp'])->diffForHumans() }}
                </div>
            @endif
        </div>

        {{-- Message Content --}}
        <div class="whitespace-pre-wrap text-sm {{ isset($message['reverted']) && $message['reverted'] ? 'line-through' : '' }}">
            {{ $message['content'] }}
        </div>

        {{-- Message Actions (on hover, only for assistant messages with ID) --}}
        @if ($message['role'] === 'assistant' && isset($message['id']) && $message['id'])
            <div class="absolute -right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col gap-1">
                <flux:button
                    wire:click="forkSession('{{ $message['id'] }}')"
                    size="sm"
                    variant="ghost"
                    icon="code-bracket"
                    title="Fork from here"
                    class="!p-1 bg-white dark:bg-gray-800 shadow-md" />
                <flux:button
                    wire:click="openDiffModal('{{ $message['id'] }}')"
                    size="sm"
                    variant="ghost"
                    icon="document-text"
                    title="View diff"
                    class="!p-1 bg-white dark:bg-gray-800 shadow-md" />
                @if (isset($message['reverted']) && $message['reverted'])
                    <flux:button
                        wire:click="unrevertMessage('{{ $message['id'] }}')"
                        size="sm"
                        variant="ghost"
                        icon="arrow-uturn-right"
                        title="Unrevert"
                        class="!p-1 bg-white dark:bg-gray-800 shadow-md" />
                @else
                    <flux:button
                        wire:click="revertMessage('{{ $message['id'] }}')"
                        size="sm"
                        variant="ghost"
                        icon="arrow-uturn-left"
                        title="Revert"
                        class="!p-1 bg-white dark:bg-gray-800 shadow-md" />
                @endif
            </div>
        @endif
    </div>
</div>
