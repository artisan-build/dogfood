<div class="flex h-full flex-col">
    <header class="mb-3 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
            Thread
        </h2>
        <button wire:click="close" type="button"
                class="text-xs text-zinc-500 hover:text-zinc-900
                       dark:hover:text-zinc-200">
            Close
        </button>
    </header>

    @php($parent = $this->parent)

    <article wire:key="thread-parent-{{ $parent->id }}"
             class="mb-3 flex gap-3 rounded-md bg-zinc-50 p-3
                    dark:bg-zinc-900">
        <div class="size-6 flex-shrink-0 rounded-full bg-zinc-200
                    dark:bg-zinc-800"></div>
        <div class="min-w-0">
            <div class="text-xs font-semibold text-zinc-800
                        dark:text-zinc-200">
                {{ $parent->member?->display_name ?? 'Unknown' }}
            </div>
            <div class="mt-0.5 text-sm text-zinc-800
                        dark:text-zinc-200">
                @if ($parent->trashed())
                    <em class="text-zinc-500">This message was deleted.</em>
                @else
                    <p class="whitespace-pre-wrap break-words">{{ $parent->body }}</p>
                @endif
            </div>
        </div>
    </article>

    <ul role="list" class="mb-3 flex min-h-0 flex-1 flex-col gap-2 overflow-y-auto">
        @forelse ($this->replies as $reply)
            <li wire:key="reply-{{ $reply->id }}" class="flex gap-2">
                <div class="size-6 flex-shrink-0 rounded-full bg-zinc-200
                            dark:bg-zinc-800"></div>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-semibold text-zinc-800
                                dark:text-zinc-200">
                        {{ $reply->member?->display_name ?? 'Unknown' }}
                    </div>
                    <p class="text-sm text-zinc-800 whitespace-pre-wrap break-words
                              dark:text-zinc-200">{{ $reply->body }}</p>
                </div>
            </li>
        @empty
            <li class="text-center text-xs text-zinc-500
                       dark:text-zinc-400">
                No replies yet.
            </li>
        @endforelse
    </ul>

    <livewire:bonfire::message-composer
        :room="$room"
        :parent-id="$parentId"
        wire:key="thread-composer-{{ $parentId }}" />
</div>
