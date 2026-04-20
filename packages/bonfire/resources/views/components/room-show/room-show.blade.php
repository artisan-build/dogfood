@php
    $isDm = str_starts_with((string) $room->slug, 'dm-');
    $dmPartner = null;
    if ($isDm) {
        $currentMember = \ArtisanBuild\Bonfire\Facades\Bonfire::memberFor(auth()->user());
        $dmPartner = $room->members()
            ->when($currentMember !== null, fn ($q) => $q->where('bonfire_members.id', '!=', $currentMember->id))
            ->first();
    }
    $displayName = $isDm && $dmPartner ? $dmPartner->display_name : $room->name;
@endphp

<div class="flex h-full min-h-0 flex-1 flex-col">
    @php
        $channelMembers = $this->channelMembers;
        $memberPreview = $channelMembers->take(3);
        $memberCount = $channelMembers->count();
    @endphp

    <header x-data="{
                showSearch: false,
                search: '',
                toggleSearch() {
                    this.showSearch = ! this.showSearch;
                    if (this.showSearch) {
                        this.$nextTick(() => this.$refs.searchInput?.focus());
                    } else {
                        this.search = '';
                        Livewire.dispatch('bonfire-search', { value: '' });
                    }
                },
                runSearch() {
                    Livewire.dispatch('bonfire-search', { value: this.search });
                },
            }"
            class="flex h-12 flex-shrink-0 items-center gap-3 border-b border-zinc-200 px-4
                   dark:border-zinc-800">
        @unless ($isDm)
            <button type="button"
                    wire:click="toggleStar"
                    title="{{ $this->isStarred ? 'Unstar this channel' : 'Star this channel' }}"
                    class="rounded p-1 {{ $this->isStarred ? 'text-amber-500' : 'text-zinc-400' }} hover:bg-zinc-100 hover:text-amber-500
                           dark:hover:bg-zinc-800">
                <flux:icon name="star" :variant="$this->isStarred ? 'solid' : 'outline'" class="size-4" />
            </button>
        @endunless

        <div class="flex min-w-0 items-center gap-2">
            @if ($isDm && $dmPartner)
                <span class="relative flex-shrink-0">
                    <img src="{{ $dmPartner->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($dmPartner->display_name) }}"
                         alt="" class="size-6 rounded bg-zinc-200 dark:bg-zinc-700">
                    <span class="absolute -right-0.5 -bottom-0.5 size-2 rounded-full border-2 border-white bg-emerald-500
                                 dark:border-zinc-950"></span>
                </span>
            @elseif ($room->isAnnouncements())
                <flux:icon name="megaphone" class="size-4 flex-shrink-0 text-amber-500" />
            @elseif ($room->isArchived())
                <flux:icon name="archive-box" class="size-4 flex-shrink-0 text-zinc-500" />
            @else
                <span class="text-zinc-500 dark:text-zinc-400">#</span>
            @endif
            <h1 class="truncate text-base font-semibold text-zinc-900 dark:text-zinc-100">
                {{ $displayName }}
            </h1>
            @if (! $isDm && $room->description)
                <span class="hidden truncate border-l border-zinc-200 pl-3 text-sm text-zinc-500
                             lg:inline
                             dark:border-zinc-800 dark:text-zinc-400">
                    {{ $room->description }}
                </span>
            @endif
        </div>

        <div class="ml-auto flex items-center gap-4">
            <div x-show="showSearch"
                 x-transition.opacity.duration.100ms
                 class="relative"
                 style="display: none;">
                <flux:icon name="magnifying-glass"
                           class="absolute left-2 top-1/2 size-3.5 -translate-y-1/2 text-zinc-400" />
                <input type="search"
                       x-ref="searchInput"
                       x-model.debounce.200ms="search"
                       @input.debounce.200ms="runSearch()"
                       @keydown.escape="toggleSearch()"
                       placeholder="Search this channel"
                       class="h-7 w-56 rounded-md border border-zinc-300 bg-white pl-7 pr-2 text-sm
                              focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500
                              dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100" />
            </div>

            @unless ($isDm)
                <button type="button"
                        title="{{ $memberCount }} {{ \Illuminate\Support\Str::plural('member', $memberCount) }}"
                        @click="$dispatch('modal-show', { name: 'channel-members' })"
                        class="flex items-center gap-1.5 rounded-md border border-zinc-200 px-2 py-1
                               hover:bg-zinc-100
                               dark:border-zinc-700 dark:hover:bg-zinc-800">
                    <span class="flex -space-x-1.5">
                        @foreach ($memberPreview as $m)
                            <img src="{{ $m->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($m->display_name) }}"
                                 alt="{{ $m->display_name }}"
                                 class="size-5 rounded bg-zinc-200 ring-2 ring-white
                                        dark:bg-zinc-700 dark:ring-zinc-900">
                        @endforeach
                    </span>
                    <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ $memberCount }}</span>
                </button>
            @endunless

            <div class="flex items-center gap-1 text-zinc-500"
                 x-data="{
                     notifyKey: 'bonfire.notify.{{ $room->id }}',
                     get notify() { return localStorage.getItem(this.notifyKey) ?? 'all'; },
                     setNotify(v) { localStorage.setItem(this.notifyKey, v); this.$refs.bell?.blur(); },
                 }">
                <flux:dropdown align="end">
                    <button type="button"
                            x-ref="bell"
                            title="Notification preferences"
                            class="rounded p-1.5 hover:bg-zinc-100 hover:text-zinc-900
                                   dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                        <flux:icon name="bell" class="size-4" />
                    </button>
                    <flux:menu>
                        <div class="px-2 pb-1 pt-2 text-[11px] font-semibold uppercase tracking-wider text-zinc-500">
                            Notifications
                        </div>
                        <flux:menu.item @click="setNotify('all')" ::icon="notify === 'all' ? 'check' : ''">
                            All new messages
                        </flux:menu.item>
                        <flux:menu.item @click="setNotify('mentions')" ::icon="notify === 'mentions' ? 'check' : ''">
                            Only @mentions
                        </flux:menu.item>
                        <flux:menu.item @click="setNotify('off')" ::icon="notify === 'off' ? 'check' : ''">
                            Off
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>

                <button type="button"
                        title="Search in channel"
                        @click="toggleSearch()"
                        :class="showSearch ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100' : ''"
                        class="rounded p-1.5 hover:bg-zinc-100 hover:text-zinc-900
                               dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                    <flux:icon name="magnifying-glass" class="size-4" />
                </button>

                <flux:dropdown align="end">
                    <button type="button"
                            title="More"
                            class="rounded p-1.5 hover:bg-zinc-100 hover:text-zinc-900
                                   dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                        <flux:icon name="ellipsis-vertical" class="size-4" />
                    </button>

                <flux:menu>
                    <flux:menu.item icon="information-circle">Open channel details</flux:menu.item>
                    <flux:menu.item icon="star" wire:click="toggleStar">
                        {{ $this->isStarred ? 'Unstar channel' : 'Star channel' }}
                    </flux:menu.item>
                    <flux:menu.item icon="bell">Edit notifications</flux:menu.item>
                    <flux:menu.separator />
                    <flux:menu.item icon="clipboard-document"
                                    x-on:click="navigator.clipboard?.writeText('{{ route('bonfire.room.show', $room) }}')">
                        Copy channel link
                    </flux:menu.item>
                    <flux:menu.item icon="magnifying-glass" @click="toggleSearch()">Search in channel</flux:menu.item>
                    @if ($room->isPrivate() && ! $isDm)
                        <flux:menu.separator />
                        <flux:menu.item icon="arrow-right-start-on-rectangle"
                                        variant="danger"
                                        wire:click="leaveChannel"
                                        wire:confirm="Leave this channel?">
                            Leave channel
                        </flux:menu.item>
                    @endif
                </flux:menu>
            </flux:dropdown>
        </div>
    </header>

    <div class="relative flex min-h-0 flex-1">
        <div class="flex min-h-0 flex-1 flex-col">
            <livewire:bonfire::message-list :room="$room" wire:key="room-{{ $room->id }}-list" />

            <div class="flex-shrink-0 border-t border-zinc-200 px-4 py-3
                        dark:border-zinc-800">
                @if ($this->canPost)
                    <livewire:bonfire::message-composer
                        :room="$room"
                        wire:key="room-{{ $room->id }}-composer" />
                @elseif ($room->isArchived())
                    <p class="rounded-md bg-zinc-100 p-3 text-center text-sm text-zinc-600
                              dark:bg-zinc-900 dark:text-zinc-400">
                        This room is archived and read-only.
                    </p>
                @elseif ($room->isAnnouncements())
                    <p class="rounded-md bg-amber-50 p-3 text-center text-sm text-amber-800
                              dark:bg-amber-950 dark:text-amber-200">
                        Only moderators and admins can post in announcement rooms.
                    </p>
                @endif
            </div>
        </div>

        @if ($openThreadId !== null)
            <aside class="flex h-full w-full max-w-md flex-shrink-0 border-l border-zinc-200
                          dark:border-zinc-800">
                <livewire:bonfire::thread-panel
                    :parent-id="$openThreadId"
                    :room="$room"
                    wire:key="thread-{{ $openThreadId }}" />
            </aside>
        @endif
    </div>

    <flux:modal name="channel-members" class="max-w-md">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">
                    @if ($isDm)
                        Direct message
                    @else
                        #{{ $room->name }}
                    @endif
                </flux:heading>
                <flux:text class="mt-1">
                    {{ $memberCount }} {{ \Illuminate\Support\Str::plural('member', $memberCount) }}
                </flux:text>
            </div>

            <ul class="max-h-80 divide-y divide-zinc-100 overflow-y-auto
                       dark:divide-zinc-800">
                @foreach ($channelMembers as $m)
                    <li class="flex items-center gap-3 py-2">
                        <span class="relative flex-shrink-0">
                            <img src="{{ $m->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($m->display_name) }}"
                                 alt="" class="size-8 rounded bg-zinc-200 dark:bg-zinc-700">
                            <span class="absolute -right-0.5 -bottom-0.5 size-2.5 rounded-full border-2 border-white bg-emerald-500
                                         dark:border-zinc-900"></span>
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $m->display_name }}
                            </div>
                            @if ($this->currentMember && $m->id === $this->currentMember->id)
                                <div class="text-xs text-zinc-500">You</div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="flex justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Close</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>
</div>
