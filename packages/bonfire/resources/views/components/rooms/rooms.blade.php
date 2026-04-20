@php
    $currentRoom = request()->route('room');
    $currentRoomId = $currentRoom instanceof \ArtisanBuild\Bonfire\Models\Room ? $currentRoom->id : null;
    $all = collect($this->visibleRooms)->reject(fn ($room) => str_starts_with($room->slug, 'dm-'));
    $starred = $all->where('is_starred', true);
    $unstarred = $all->where('is_starred', false);
    [$activeRooms, $archivedRooms] = $unstarred->partition(fn ($room) => ! $room->isArchived());
    $dms = $this->directMessageMembers;
@endphp

<div x-data="{
         sections: JSON.parse(localStorage.getItem('bonfire.sidebar') ?? '{}'),
         toggle(key) {
             this.sections[key] = ! this.isOpen(key);
             localStorage.setItem('bonfire.sidebar', JSON.stringify(this.sections));
         },
         isOpen(key) {
             return this.sections[key] !== false;
         },
     }"
     class="flex min-h-0 flex-1 flex-col text-sm">

    <div class="flex min-h-0 flex-1 flex-col overflow-y-auto pt-2">

        <section class="px-2">
            <button type="button"
                    @click="toggle('starred')"
                    class="group flex w-full items-center gap-1 rounded px-2 py-1 text-xs font-semibold uppercase
                           tracking-wider text-zinc-500 hover:bg-zinc-200/60 hover:text-zinc-900
                           dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                <flux:icon name="chevron-down"
                           class="size-3 transition"
                           ::class="{ '-rotate-90': ! isOpen('starred') }" />
                <flux:icon name="star" class="size-3.5" />
                <span class="ml-0.5 normal-case tracking-normal">Starred</span>
            </button>
            <div x-show="isOpen('starred')">
                <ul class="flex flex-col">
                    @forelse ($starred as $room)
                        <li wire:key="starred-{{ $room->id }}">
                            <a href="{{ route('bonfire.room.show', $room) }}"
                               class="group flex items-center gap-2 rounded px-2 py-1
                                      {{ $room->id === $currentRoomId
                                          ? 'bg-sky-100 text-sky-900 dark:bg-sky-900/40 dark:text-sky-100'
                                          : 'text-zinc-700 hover:bg-zinc-200/60 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-zinc-100' }}">
                                @if ($room->isAnnouncements())
                                    <flux:icon name="megaphone" class="size-4 flex-shrink-0 text-amber-500" />
                                @else
                                    <span class="w-4 flex-shrink-0 text-center text-zinc-400">#</span>
                                @endif
                                <span class="truncate {{ $room->has_unread ? 'font-semibold text-zinc-900 dark:text-zinc-100' : '' }}">
                                    {{ $room->name }}
                                </span>
                                <button type="button"
                                        wire:click.stop.prevent="toggleStar({{ $room->id }})"
                                        class="ml-auto text-amber-500 hover:text-amber-600">
                                    <flux:icon name="star" variant="solid" class="size-3.5" />
                                </button>
                            </a>
                        </li>
                    @empty
                        <li class="px-2 py-2 text-xs text-zinc-400 dark:text-zinc-500">
                            Drag and drop important stuff here
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>

        <div class="mx-3 my-3 h-px bg-zinc-200 dark:bg-zinc-800"></div>

        <section class="px-2">
            <button type="button"
                    @click="toggle('channels')"
                    class="group flex w-full items-center gap-1 rounded px-2 py-1 text-xs font-semibold uppercase
                           tracking-wider text-zinc-500 hover:bg-zinc-200/60 hover:text-zinc-900
                           dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                <flux:icon name="chevron-down"
                           class="size-3 transition"
                           ::class="{ '-rotate-90': ! isOpen('channels') }" />
                <span class="ml-1 normal-case tracking-normal">Channels</span>
            </button>
            <div x-show="isOpen('channels')">
                <ul class="flex flex-col">
                    @forelse ($activeRooms as $room)
                        @php
                            $isActive = $room->id === $currentRoomId;
                            $hasUnread = (bool) ($room->has_unread ?? false);
                        @endphp
                        <li wire:key="channel-{{ $room->id }}" class="group">
                            <a href="{{ route('bonfire.room.show', $room) }}"
                               class="flex items-center gap-2 rounded px-2 py-1
                                      {{ $isActive
                                          ? 'bg-sky-100 text-sky-900 dark:bg-sky-900/40 dark:text-sky-100'
                                          : 'text-zinc-700 hover:bg-zinc-200/60 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-zinc-100' }}">
                                @if ($room->isAnnouncements())
                                    <flux:icon name="megaphone" class="size-4 flex-shrink-0 text-amber-500" />
                                @else
                                    <span class="w-4 flex-shrink-0 text-center text-zinc-400 group-hover:text-zinc-500">#</span>
                                @endif
                                <span class="truncate {{ $hasUnread ? 'font-semibold text-zinc-900 dark:text-zinc-100' : '' }}">
                                    {{ $room->name }}
                                </span>
                                @if ($hasUnread && ! $isActive)
                                    <span class="ml-auto size-1.5 flex-shrink-0 rounded-full bg-sky-500"></span>
                                @endif
                                <button type="button"
                                        wire:click.stop.prevent="toggleStar({{ $room->id }})"
                                        class="ml-auto hidden text-zinc-400 group-hover:block hover:text-amber-500">
                                    <flux:icon name="star" class="size-3.5" />
                                </button>
                            </a>
                        </li>
                    @empty
                        <li class="px-2 py-1 text-xs text-zinc-500">No channels yet.</li>
                    @endforelse
                    <li>
                        <button type="button"
                                class="flex w-full items-center gap-2 rounded px-2 py-1 text-zinc-500
                                       hover:bg-zinc-200/60 hover:text-zinc-900
                                       dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                            <flux:icon name="plus" class="size-4" />
                            <span>Add channels</span>
                        </button>
                    </li>
                </ul>
            </div>
        </section>

        @if ($archivedRooms->isNotEmpty())
            <section class="mt-3 px-2">
                <button type="button"
                        @click="toggle('archived')"
                        class="group flex w-full items-center gap-1 rounded px-2 py-1 text-xs font-semibold uppercase
                               tracking-wider text-zinc-400 hover:bg-zinc-200/60 hover:text-zinc-900
                               dark:text-zinc-500 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                    <flux:icon name="chevron-down"
                               class="size-3 transition"
                               ::class="{ '-rotate-90': ! isOpen('archived') }" />
                    <span class="ml-1 normal-case tracking-normal">Archived</span>
                </button>
                <div x-show="isOpen('archived')">
                    <ul class="flex flex-col">
                        @foreach ($archivedRooms as $room)
                            @php
                                $isActive = $room->id === $currentRoomId;
                            @endphp
                            <li wire:key="archived-{{ $room->id }}">
                                <a href="{{ route('bonfire.room.show', $room) }}"
                                   class="group flex items-center gap-2 rounded px-2 py-1
                                          {{ $isActive
                                              ? 'bg-sky-100 text-sky-900 dark:bg-sky-900/40 dark:text-sky-100'
                                              : 'text-zinc-500 hover:bg-zinc-200/60 hover:text-zinc-800 dark:text-zinc-500 dark:hover:bg-zinc-800 dark:hover:text-zinc-300' }}">
                                    <flux:icon name="archive-box" class="size-4 flex-shrink-0" />
                                    <span class="truncate">{{ $room->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        <div class="mx-3 my-3 h-px bg-zinc-200 dark:bg-zinc-800"></div>

        <section class="px-2">
            <button type="button"
                    @click="toggle('dms')"
                    class="group flex w-full items-center gap-1 rounded px-2 py-1 text-xs font-semibold uppercase
                           tracking-wider text-zinc-500 hover:bg-zinc-200/60 hover:text-zinc-900
                           dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                <flux:icon name="chevron-down"
                           class="size-3 transition"
                           ::class="{ '-rotate-90': ! isOpen('dms') }" />
                <flux:icon name="at-symbol" class="size-3.5" />
                <span class="ml-0.5 normal-case tracking-normal">Direct messages</span>
            </button>
            <div x-show="isOpen('dms')">
                <ul class="flex flex-col">
                    @forelse ($dms as $member)
                        <li wire:key="dm-{{ $member->id }}">
                            <button type="button"
                                    wire:click="openDm({{ $member->id }})"
                                    class="flex w-full items-center gap-2 rounded px-2 py-1 text-left
                                           text-zinc-700 hover:bg-zinc-200/60 hover:text-zinc-900
                                           dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                <span class="relative flex-shrink-0">
                                    <img src="{{ $member->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->display_name) }}"
                                         alt="" class="size-5 rounded bg-zinc-200 dark:bg-zinc-700">
                                    <span class="absolute -right-0.5 -bottom-0.5 size-2 rounded-full border-2 border-zinc-50 bg-emerald-500
                                                 dark:border-zinc-900"></span>
                                </span>
                                <span class="truncate">{{ $member->display_name }}</span>
                            </button>
                        </li>
                    @empty
                        <li class="px-2 py-1 text-xs text-zinc-400 dark:text-zinc-500">No one else here yet.</li>
                    @endforelse
                    <li>
                        <button type="button"
                                class="flex w-full items-center gap-2 rounded px-2 py-1 text-zinc-500
                                       hover:bg-zinc-200/60 hover:text-zinc-900
                                       dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                            <flux:icon name="plus" class="size-4" />
                            <span>Invite people</span>
                        </button>
                    </li>
                </ul>
            </div>
        </section>

        <div class="pb-4"></div>
    </div>

    <flux:modal name="new-message" class="max-w-lg"
                x-data="{
                    query: '',
                    channels: @js($activeRooms->map(fn($r) => ['id' => $r->id, 'name' => $r->name, 'slug' => $r->slug])->values()->all()),
                    members: @js($dms->map(fn($m) => ['id' => $m->id, 'display_name' => $m->display_name, 'avatar_url' => $m->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($m->display_name)])->values()->all()),
                    get filteredChannels() {
                        const q = this.query.trim().replace(/^#/, '').toLowerCase();
                        if (q === '') return this.channels.slice(0, 6);
                        return this.channels.filter(c => c.name.toLowerCase().includes(q)).slice(0, 6);
                    },
                    get filteredMembers() {
                        const q = this.query.trim().replace(/^@/, '').toLowerCase();
                        if (q === '') return this.members.slice(0, 6);
                        return this.members.filter(m => m.display_name.toLowerCase().includes(q)).slice(0, 6);
                    },
                    goChannel(slug) {
                        this.$dispatch('modal-close', { name: 'new-message' });
                        window.location.href = '{{ url(config('bonfire.route_prefix', 'bonfire')) }}/' + slug;
                    },
                    goMember(id) {
                        this.$dispatch('modal-close', { name: 'new-message' });
                        this.$wire.openDm(id);
                    },
                }"
                @close="query = ''">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">New message</flux:heading>
                <flux:text class="mt-1">
                    Start a conversation with a channel or teammate.
                </flux:text>
            </div>

            <flux:input x-model="query"
                        placeholder="#channel, @person"
                        icon="magnifying-glass"
                        autofocus />

            <div class="max-h-80 overflow-y-auto">
                <template x-if="filteredChannels.length > 0">
                    <div>
                        <div class="px-1 pb-1 pt-2 text-[11px] font-semibold uppercase tracking-wider text-zinc-500
                                    dark:text-zinc-400">Channels</div>
                        <ul class="flex flex-col">
                            <template x-for="channel in filteredChannels" :key="'c-' + channel.id">
                                <li>
                                    <button type="button"
                                            @click="goChannel(channel.slug)"
                                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-left text-sm
                                                   hover:bg-zinc-100
                                                   dark:hover:bg-zinc-800">
                                        <span class="w-4 flex-shrink-0 text-center text-zinc-400">#</span>
                                        <span x-text="channel.name" class="truncate"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>

                <template x-if="filteredMembers.length > 0">
                    <div>
                        <div class="px-1 pb-1 pt-3 text-[11px] font-semibold uppercase tracking-wider text-zinc-500
                                    dark:text-zinc-400">People</div>
                        <ul class="flex flex-col">
                            <template x-for="member in filteredMembers" :key="'m-' + member.id">
                                <li>
                                    <button type="button"
                                            @click="goMember(member.id)"
                                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-left text-sm
                                                   hover:bg-zinc-100
                                                   dark:hover:bg-zinc-800">
                                        <img :src="member.avatar_url" alt="" class="size-5 flex-shrink-0 rounded bg-zinc-200 dark:bg-zinc-700">
                                        <span x-text="member.display_name" class="truncate"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>

                <template x-if="filteredChannels.length === 0 && filteredMembers.length === 0">
                    <div class="px-2 py-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
                        No matches.
                    </div>
                </template>
            </div>
        </div>
    </flux:modal>
</div>
