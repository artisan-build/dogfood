<div class="flex min-h-0 flex-1 flex-col overflow-y-auto"
     x-data="{
         pinned: true,
         typing: {},
         init() {
             $el.scrollTop = $el.scrollHeight;
             if (typeof window.Echo === 'undefined') return;
             window.Echo.join('bonfire.room.{{ $room->id }}')
                 .listenForWhisper('user.typing', (e) => {
                     if (!e || !e.member_id) return;
                     this.typing[e.member_id] = {
                         name: e.display_name,
                         at: Date.now(),
                     };
                     setTimeout(() => {
                         const entry = this.typing[e.member_id];
                         if (entry && Date.now() - entry.at >= 3000) {
                             delete this.typing[e.member_id];
                         }
                     }, 3100);
                 });
         },
         get typingLabel() {
             const names = Object.values(this.typing).map(t => t.name).filter(Boolean);
             if (names.length === 0) return '';
             if (names.length === 1) return names[0] + ' is typing…';
             if (names.length === 2) return names.join(' and ') + ' are typing…';
             return names.length + ' people are typing…';
         }
     }">
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
                            <div class="prose prose-sm max-w-none break-words dark:prose-invert">
                                {!! app(\ArtisanBuild\Bonfire\Support\MarkdownRenderer::class)->render($message->body, $message->tenant_id) !!}
                            </div>
                            @if ($message->relationLoaded('linkPreview') && $message->linkPreview && ! $message->linkPreview->failed)
                                <a href="{{ $message->linkPreview->url }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="mt-2 flex gap-3 rounded-md border border-zinc-200 p-3
                                          hover:bg-zinc-50
                                          dark:border-zinc-800 dark:hover:bg-zinc-900">
                                    @if ($message->linkPreview->image_url)
                                        <img src="{{ $message->linkPreview->image_url }}"
                                             alt=""
                                             class="size-16 flex-shrink-0 rounded object-cover">
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        @if ($message->linkPreview->title)
                                            <div class="truncate text-sm font-medium text-zinc-900
                                                        dark:text-zinc-100">
                                                {{ $message->linkPreview->title }}
                                            </div>
                                        @endif
                                        @if ($message->linkPreview->description)
                                            <p class="mt-0.5 line-clamp-2 text-xs text-zinc-600
                                                      dark:text-zinc-400">
                                                {{ $message->linkPreview->description }}
                                            </p>
                                        @endif
                                        @if ($message->linkPreview->site_name)
                                            <div class="mt-1 text-[11px] uppercase tracking-wide text-zinc-500">
                                                {{ $message->linkPreview->site_name }}
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @endif
                            @if ($message->attachments->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($message->attachments as $attachment)
                                        @if ($attachment->isImage())
                                            <a href="{{ route('bonfire.attachments.show', $attachment) }}"
                                               target="_blank"
                                               rel="noopener">
                                                <img src="{{ route('bonfire.attachments.show', $attachment) }}"
                                                     alt="{{ $attachment->filename }}"
                                                     class="max-h-40 rounded border border-zinc-200
                                                            dark:border-zinc-800">
                                            </a>
                                        @else
                                            <a href="{{ route('bonfire.attachments.show', $attachment) }}"
                                               class="flex items-center gap-2 rounded-md border border-zinc-200 p-2 text-xs
                                                      hover:bg-zinc-50
                                                      dark:border-zinc-800 dark:hover:bg-zinc-900">
                                                <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                                    {{ $attachment->filename }}
                                                </span>
                                                <span class="text-zinc-500">
                                                    {{ number_format($attachment->size / 1024, 1) }} KB
                                                </span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
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
                            @if ($this->canDelete($message))
                                <button wire:click="deleteMessage({{ $message->id }})"
                                        type="button"
                                        wire:confirm="Delete this message?"
                                        class="text-rose-600 hover:text-rose-700
                                               dark:text-rose-400 dark:hover:text-rose-300">
                                    Delete
                                </button>
                            @endif
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

    <div x-show="typingLabel"
         x-text="typingLabel"
         class="px-3 py-1 text-xs italic text-zinc-500 dark:text-zinc-400"></div>

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
