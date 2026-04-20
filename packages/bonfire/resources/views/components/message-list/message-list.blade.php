<div class="flex min-h-0 flex-1 flex-col overflow-y-auto"
     x-data="{
         pinned: true,
         typing: {},
         pendingDeleteId: null,
         pendingDeletePreview: '',
         askDelete(id, preview) {
             this.pendingDeleteId = id;
             this.pendingDeletePreview = preview;
             this.$dispatch('modal-show', { name: 'delete-message' });
         },
         confirmDelete() {
             if (this.pendingDeleteId === null) return;
             this.$wire.deleteMessage(this.pendingDeleteId);
             this.$dispatch('modal-close', { name: 'delete-message' });
             this.pendingDeleteId = null;
             this.pendingDeletePreview = '';
         },
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
    @php
        $previous = null;
        $groupThresholdSeconds = 5 * 60;
    @endphp

    @if ($this->messages->hasMorePages())
        <div class="p-3 text-center">
            <button wire:click="$set('perPage', {{ $this->perPage + 40 }})"
                    type="button"
                    class="text-xs text-zinc-600 hover:text-zinc-900
                           dark:text-zinc-400 dark:hover:text-zinc-200">
                Load earlier messages
            </button>
        </div>
    @endif

    <ul role="list" class="flex flex-col py-2">
        @forelse ($this->messages as $message)
            @php
                $sameAuthor = $previous
                    && $previous->member_id === $message->member_id
                    && $previous->created_at
                    && $message->created_at
                    && $previous->created_at->diffInSeconds($message->created_at) <= $groupThresholdSeconds;
                $previous = $message;
            @endphp
            <li wire:key="message-{{ $message->id }}"
                class="group relative flex gap-3 px-4 {{ $sameAuthor ? 'py-0.5' : 'mt-3 py-1 first:mt-0' }}
                       hover:bg-zinc-50 dark:hover:bg-zinc-900/50">
                <div class="flex w-10 flex-shrink-0 justify-center">
                    @if ($sameAuthor)
                        <time class="mt-1 hidden w-full text-center text-[10px] leading-none text-zinc-400
                                     group-hover:block
                                     dark:text-zinc-500">
                            {{ $message->created_at?->format('g:i') }}
                        </time>
                    @else
                        <img src="{{ $message->member?->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->member?->display_name ?? '?') }}"
                             alt=""
                             class="size-9 rounded bg-zinc-200 dark:bg-zinc-800">
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    @unless ($sameAuthor)
                        <div class="flex items-baseline gap-2">
                            <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ $message->member?->display_name ?? 'Unknown' }}
                            </span>
                            <time class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $message->created_at?->format('g:i A') }}
                            </time>
                        </div>
                    @endunless

                    <div class="text-sm text-zinc-800 dark:text-zinc-200">
                        @if ($message->trashed())
                            <em class="text-zinc-500">This message was deleted.</em>
                        @else
                            <div class="bonfire-message-body max-w-none break-words">
                                @php
                                    $bodyLooksLikeHtml = preg_match('/<\\w+[^>]*>/', (string) $message->body) === 1;
                                @endphp
                                @if ($bodyLooksLikeHtml)
                                    {!! $message->body !!}
                                @else
                                    {!! app(\ArtisanBuild\Bonfire\Support\MarkdownRenderer::class)->render($message->body, $message->tenant_id) !!}
                                @endif
                            </div>

                            @if ($message->relationLoaded('linkPreview') && $message->linkPreview && ! $message->linkPreview->failed)
                                <a href="{{ $message->linkPreview->url }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="mt-2 flex max-w-lg gap-3 rounded-md border border-zinc-200 p-3
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

                    @if (! $message->trashed() && $message->replies->isNotEmpty())
                        <button wire:click="openThread({{ $message->id }})"
                                type="button"
                                class="mt-1 inline-flex items-center gap-1.5 rounded border border-transparent
                                       px-1.5 py-0.5 text-xs font-medium text-sky-700
                                       hover:border-zinc-200 hover:bg-white
                                       dark:text-sky-300 dark:hover:border-zinc-700 dark:hover:bg-zinc-900">
                            <flux:icon name="chat-bubble-left-right" class="size-3.5" />
                            {{ $message->replies->count() }}
                            {{ \Illuminate\Support\Str::plural('reply', $message->replies->count()) }}
                        </button>
                    @endif
                </div>

                @if (! $message->trashed())
                    <div class="pointer-events-none absolute right-4 -top-3 z-10 hidden items-center gap-0.5
                                rounded-md border border-zinc-200 bg-white p-0.5 shadow-sm
                                group-hover:pointer-events-auto group-hover:flex
                                dark:border-zinc-700 dark:bg-zinc-900">
                        <button wire:click="openThread({{ $message->id }})"
                                type="button"
                                title="Reply in thread"
                                class="rounded p-1 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900
                                       dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                            <flux:icon name="chat-bubble-left-right" class="size-4" />
                        </button>
                        @if ($this->canDelete($message))
                            <button
                                    type="button"
                                    @click="askDelete({{ $message->id }}, @js(\Illuminate\Support\Str::limit($message->body, 120)))"
                                    title="Delete"
                                    class="rounded p-1 text-zinc-500 hover:bg-rose-50 hover:text-rose-600
                                           dark:text-zinc-400 dark:hover:bg-rose-950 dark:hover:text-rose-400">
                                <flux:icon name="trash" class="size-4" />
                            </button>
                        @endif
                    </div>
                @endif
            </li>
        @empty
            <li class="p-6 text-center text-sm text-zinc-600 dark:text-zinc-400">
                No messages yet. Say hello.
            </li>
        @endforelse
    </ul>

    <div x-show="typingLabel"
         x-text="typingLabel"
         class="px-4 py-1 text-xs italic text-zinc-500 dark:text-zinc-400"></div>

    <flux:modal name="delete-message" class="max-w-md">
        <div class="space-y-5">
            <div>
                <flux:heading size="lg">Delete message?</flux:heading>
                <flux:text class="mt-2">
                    This will remove the message for everyone. You can't undo this.
                </flux:text>
            </div>

            <div x-show="pendingDeletePreview"
                 class="rounded-md border border-zinc-200 bg-zinc-50 p-3 text-sm text-zinc-700
                        dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300">
                <span x-text="pendingDeletePreview"></span>
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" @click="confirmDelete()">Delete</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
