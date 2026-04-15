<div class="mx-auto w-full max-w-5xl px-4 py-8
            sm:px-6
            lg:px-8">
    <h1 class="text-2xl font-semibold tracking-tight
               sm:text-3xl">
        Admin
    </h1>

    <div class="mt-6 flex gap-2 border-b border-zinc-200 dark:border-zinc-800">
        <button type="button"
                wire:click="$set('tab', 'rooms')"
                @class([
                    'border-b-2 px-3 py-2 text-sm font-medium',
                    'border-zinc-900 text-zinc-900 dark:border-zinc-100 dark:text-zinc-100' => $tab === 'rooms',
                    'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' => $tab !== 'rooms',
                ])>
            Rooms
        </button>
        <button type="button"
                wire:click="$set('tab', 'members')"
                @class([
                    'border-b-2 px-3 py-2 text-sm font-medium',
                    'border-zinc-900 text-zinc-900 dark:border-zinc-100 dark:text-zinc-100' => $tab === 'members',
                    'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' => $tab !== 'members',
                ])>
            Members
        </button>
    </div>

    @if ($tab === 'rooms')
        <section class="mt-6 space-y-6">
            <form wire:submit="createRoom"
                  class="rounded-md border border-zinc-200 p-4
                         dark:border-zinc-800">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                    Create room
                </h2>
                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    <input wire:model="newName"
                           type="text"
                           placeholder="Name"
                           class="rounded-md border border-zinc-300 bg-white p-2 text-sm
                                  dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                    <input wire:model="newDescription"
                           type="text"
                           placeholder="Description (optional)"
                           class="rounded-md border border-zinc-300 bg-white p-2 text-sm
                                  dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                </div>
                <div class="mt-3 flex flex-wrap gap-4 text-xs text-zinc-700 dark:text-zinc-300">
                    <label class="flex items-center gap-2">
                        <input wire:model="newPrivate" type="checkbox"> Private
                    </label>
                    <label class="flex items-center gap-2">
                        <input wire:model="newArchived" type="checkbox"> Archived
                    </label>
                    <label class="flex items-center gap-2">
                        <input wire:model="newAnnouncements" type="checkbox"> Announcements
                    </label>
                </div>
                <div class="mt-3 flex justify-end">
                    <flux:button type="submit" variant="primary" size="sm">Create</flux:button>
                </div>
            </form>

            <ul role="list" class="divide-y divide-zinc-100 rounded-md border border-zinc-200
                                   dark:divide-zinc-800 dark:border-zinc-800">
                @forelse ($this->rooms as $room)
                    <li wire:key="admin-room-{{ $room->id }}" class="grid gap-3 p-4 sm:grid-cols-[1fr_auto]">
                        <div class="space-y-2">
                            <input value="{{ $room->name }}"
                                   wire:change="updateRoom({{ $room->id }}, 'name', $event.target.value)"
                                   class="w-full rounded-md border border-zinc-300 bg-white p-2 text-sm font-medium
                                          dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                            <input value="{{ $room->description }}"
                                   wire:change="updateRoom({{ $room->id }}, 'description', $event.target.value)"
                                   placeholder="Description"
                                   class="w-full rounded-md border border-zinc-300 bg-white p-2 text-xs
                                          dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                            <div class="text-xs text-zinc-500">{{ $room->slug }}</div>
                        </div>
                        <div class="flex flex-wrap gap-3 text-xs text-zinc-700
                                    dark:text-zinc-300">
                            <label class="flex items-center gap-1">
                                <input type="checkbox"
                                       @checked($room->isPrivate())
                                       wire:change="updateRoom({{ $room->id }}, 'private', $event.target.checked)">
                                Private
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="checkbox"
                                       @checked($room->isArchived())
                                       wire:change="updateRoom({{ $room->id }}, 'archived', $event.target.checked)">
                                Archived
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="checkbox"
                                       @checked($room->isAnnouncements())
                                       wire:change="updateRoom({{ $room->id }}, 'announcements', $event.target.checked)">
                                Announcements
                            </label>
                        </div>
                    </li>
                @empty
                    <li class="p-6 text-center text-sm text-zinc-500">No rooms yet.</li>
                @endforelse
            </ul>
        </section>
    @else
        <section class="mt-6">
            <ul role="list" class="divide-y divide-zinc-100 rounded-md border border-zinc-200
                                   dark:divide-zinc-800 dark:border-zinc-800">
                @forelse ($this->members as $member)
                    <li wire:key="admin-member-{{ $member->id }}"
                        class="flex flex-wrap items-center gap-3 p-4">
                        <div class="min-w-0 flex-1">
                            <div class="truncate text-sm font-medium text-zinc-900
                                        dark:text-zinc-100">
                                {{ $member->display_name }}
                            </div>
                            <div class="text-xs text-zinc-500">
                                {{ class_basename($member->memberable_type) }}#{{ $member->memberable_id }}
                            </div>
                        </div>
                        <select wire:change="changeRole({{ $member->id }}, $event.target.value)"
                                class="rounded-md border border-zinc-300 bg-white p-1 text-xs
                                       dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                            @foreach (\ArtisanBuild\Bonfire\Enums\BonfireRole::cases() as $role)
                                <option value="{{ $role->value }}" @selected($member->role === $role)>
                                    {{ ucfirst($role->value) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button"
                                wire:click="toggleActive({{ $member->id }})"
                                @class([
                                    'rounded-md px-2 py-1 text-xs font-medium',
                                    'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' => $member->is_active,
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' => !$member->is_active,
                                ])>
                            {{ $member->is_active ? 'Deactivate' : 'Reactivate' }}
                        </button>
                    </li>
                @empty
                    <li class="p-6 text-center text-sm text-zinc-500">No members yet.</li>
                @endforelse
            </ul>
        </section>
    @endif
</div>
