<div class="mx-auto flex h-[calc(100vh-1rem)] w-full max-w-5xl flex-col px-4 py-6
            sm:px-6
            lg:px-8">
    <header class="mb-4 flex items-center justify-between border-b border-zinc-200 pb-3
                   dark:border-zinc-800">
        <div class="min-w-0">
            <a href="{{ route('bonfire.index') }}"
               class="text-xs text-zinc-500 hover:underline
                      dark:text-zinc-400">
                ← All rooms
            </a>
            <h1 class="mt-1 flex items-center gap-2 text-xl font-semibold tracking-tight
                       sm:text-2xl">
                @if ($room->isAnnouncements())
                    <flux:icon name="megaphone" class="size-5 text-amber-500" />
                @endif
                @if ($room->isArchived())
                    <flux:icon name="archive-box" class="size-5 text-zinc-500" />
                @endif
                {{ $room->name }}
            </h1>
            @if ($room->description)
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ $room->description }}
                </p>
            @endif
        </div>
    </header>

    <div class="relative flex min-h-0 flex-1 gap-4">
        <div class="flex min-h-0 flex-1 flex-col">
            <livewire:bonfire::message-list :room="$room" wire:key="room-{{ $room->id }}-list" />

            <div class="mt-3 border-t border-zinc-200 pt-3
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
            <aside class="w-full max-w-md border-l border-zinc-200 pl-4
                          dark:border-zinc-800">
                <livewire:bonfire::thread-panel
                    :parent-id="$openThreadId"
                    :room="$room"
                    wire:key="thread-{{ $openThreadId }}" />
            </aside>
        @endif
    </div>
</div>
