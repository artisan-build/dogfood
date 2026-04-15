<div class="flex min-h-0 flex-1 flex-col overflow-y-auto"
     x-data="{ pinned: true }"
     x-init="$el.scrollTop = $el.scrollHeight">
    <ul role="list" class="divide-y divide-zinc-100 dark:divide-zinc-800">
        @forelse ($this->messages as $message)
            <li wire:key="message-{{ $message->id }}" class="flex gap-3 p-3">
                <img src="{{ $message->member?->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->member?->display_name ?? '?') }}"
                     alt=""
                     class="size-8 flex-shrink-0 rounded-full bg-zinc-200
                            dark:bg-zinc-800">
                <div class="min-w-0 flex-1">
                    <div class="flex items-baseline gap-2">
                        <span class="text-sm font-semibold text-zinc-900
                                     dark:text-zinc-100">
                            {{ $message->member?->display_name ?? 'Unknown' }}
                        </span>
                        <time class="text-xs text-zinc-500 dark:text-zinc-400">
                            {{ $message->created_at?->diffForHumans() }}
                        </time>
                    </div>
                    <div class="mt-1 text-sm text-zinc-800
                                dark:text-zinc-200">
                        @if ($message->trashed())
                            <em class="text-zinc-500">This message was deleted.</em>
                        @else
                            {{-- Rendered verbatim in Phase 2; Phase 3 wires MarkdownRenderer. --}}
                            <p class="whitespace-pre-wrap break-words">{{ $message->body }}</p>
                        @endif
                    </div>
                    @if (! $message->trashed())
                        <div class="mt-2 flex items-center gap-3 text-xs text-zinc-500
                                    dark:text-zinc-400">
                            <button wire:click="openThread({{ $message->id }})"
                                    type="button"
                                    class="hover:text-zinc-900
                                           dark:hover:text-zinc-200">
                                Reply
                                @if ($message->replies->isNotEmpty())
                                    <span class="ml-1 rounded-full bg-zinc-100 px-1.5 py-0.5 text-zinc-700
                                                 dark:bg-zinc-800 dark:text-zinc-300">
                                        {{ $message->replies->count() }}
                                    </span>
                                @endif
                            </button>
                        </div>
                    @endif
                </div>
            </li>
        @empty
            <li class="p-6 text-center text-sm text-zinc-600
                       dark:text-zinc-400">
                No messages yet. Say hello.
            </li>
        @endforelse
    </ul>

    @if ($this->messages->hasMorePages())
        <div class="p-3 text-center">
            <button wire:click="$set('perPage', {{ $this->perPage + 40 }})"
                    type="button"
                    class="text-xs text-zinc-600 hover:text-zinc-900
                           dark:text-zinc-400 dark:hover:text-zinc-200">
                Load more
            </button>
        </div>
    @endif
</div>
