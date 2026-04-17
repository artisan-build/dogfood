<div class="mx-auto w-full max-w-3xl px-4 py-8
            sm:px-6
            lg:px-8">
    <header class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold tracking-tight
                   sm:text-3xl">
            Rooms
        </h1>
    </header>

    <ul role="list" class="divide-y divide-zinc-200 rounded-lg border border-zinc-200
                           dark:divide-zinc-800 dark:border-zinc-800">
        @forelse ($this->visibleRooms as $room)
            <li wire:key="room-{{ $room->id }}" class="flex items-start justify-between gap-4 p-4">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        @if ($room->isAnnouncements())
                            <flux:icon name="megaphone" class="size-4 text-amber-500" />
                        @endif
                        @if ($room->isArchived())
                            <flux:icon name="archive-box" class="size-4 text-zinc-500" />
                        @endif
                        <a href="{{ route('bonfire.room.show', $room) }}"
                           class="truncate text-base font-medium text-zinc-900
                                  hover:underline
                                  dark:text-zinc-100">
                            {{ $room->name }}
                        </a>
                    </div>
                    @if ($room->description)
                        <p class="mt-1 truncate text-sm text-zinc-600
                                  dark:text-zinc-400">
                            {{ \Illuminate\Support\Str::limit($room->description, 140) }}
                        </p>
                    @endif
                </div>
            </li>
        @empty
            <li class="p-6 text-center text-sm text-zinc-600
                       dark:text-zinc-400">
                No rooms available yet.
            </li>
        @endforelse
    </ul>
</div>
