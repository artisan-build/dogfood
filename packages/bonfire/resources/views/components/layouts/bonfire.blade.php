<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Bonfire' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="h-full overflow-hidden bg-white text-zinc-900 antialiased
                 dark:bg-zinc-950 dark:text-zinc-100"
          x-data="{
              sidebarOpen: (localStorage.getItem('bonfire.sidebarOpen') ?? 'true') === 'true',
              sidebarWidth: parseInt(localStorage.getItem('bonfire.sidebarWidth') ?? '260', 10),
              resizing: false,
              init() {
                  this.$watch('sidebarOpen', v => localStorage.setItem('bonfire.sidebarOpen', v));
                  this.$watch('sidebarWidth', v => localStorage.setItem('bonfire.sidebarWidth', v));
              },
              startResize(e) {
                  this.resizing = true;
                  document.body.style.cursor = 'col-resize';
                  document.body.style.userSelect = 'none';
                  const move = (ev) => {
                      if (! this.resizing) return;
                      const w = Math.max(200, Math.min(480, ev.clientX));
                      this.sidebarWidth = w;
                  };
                  const up = () => {
                      this.resizing = false;
                      document.body.style.cursor = '';
                      document.body.style.userSelect = '';
                      window.removeEventListener('mousemove', move);
                      window.removeEventListener('mouseup', up);
                  };
                  window.addEventListener('mousemove', move);
                  window.addEventListener('mouseup', up);
              },
          }">
        <div class="flex h-full">
            <aside :style="sidebarOpen ? 'width: ' + sidebarWidth + 'px' : 'width: 0px'"
                   :class="resizing ? '' : 'transition-[width] duration-200 ease-out'"
                   class="relative flex h-full flex-shrink-0 flex-col overflow-hidden border-r border-zinc-200
                          bg-zinc-50 text-zinc-900
                          dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-100">
                <div :style="'width: ' + sidebarWidth + 'px'"
                     class="flex h-full min-h-0 flex-col">
                    <div class="flex h-12 flex-shrink-0 items-center justify-between gap-2 border-b border-zinc-200 px-3
                                dark:border-zinc-800">
                        <flux:dropdown>
                            <button type="button"
                                    class="group flex min-w-0 flex-1 items-center gap-1 rounded px-1.5 py-1
                                           text-left text-sm font-semibold
                                           hover:bg-zinc-200/60 dark:hover:bg-zinc-800">
                                <span class="truncate">{{ config('app.name', 'Bonfire') }}</span>
                                <flux:icon name="chevron-down" class="size-3.5 text-zinc-500" />
                            </button>

                            <flux:menu>
                                <flux:menu.item icon="plus">Invite people</flux:menu.item>
                                <flux:menu.item icon="cog-6-tooth">Workspace settings</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="arrow-right-start-on-rectangle" href="{{ route('logout', absolute: false) }}"
                                                wire:navigate>Sign out</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>

                        <div class="flex items-center gap-1 text-zinc-500"
                             x-data="{
                                 filter: localStorage.getItem('bonfire.filter') ?? 'all',
                                 sort: localStorage.getItem('bonfire.sort') ?? 'alpha',
                                 setFilter(v) {
                                     this.filter = v;
                                     localStorage.setItem('bonfire.filter', v);
                                     Livewire.dispatch('bonfire-filter', {value: v});
                                 },
                                 setSort(v) {
                                     this.sort = v;
                                     localStorage.setItem('bonfire.sort', v);
                                     Livewire.dispatch('bonfire-sort', {value: v});
                                 },
                                 init() {
                                     if (this.filter !== 'all') Livewire.dispatch('bonfire-filter', {value: this.filter});
                                     if (this.sort !== 'alpha') Livewire.dispatch('bonfire-sort', {value: this.sort});
                                 },
                             }">
                            <flux:dropdown>
                                <button type="button"
                                        title="Filter &amp; sort"
                                        class="rounded p-1 hover:bg-zinc-200/60 hover:text-zinc-900
                                               dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                    <flux:icon name="cog-6-tooth" class="size-4" />
                                </button>

                                <flux:menu>
                                    <div class="px-2 py-1 text-[11px] font-semibold uppercase tracking-wider text-zinc-500">
                                        Filter by
                                    </div>
                                    <flux:menu.item @click="setFilter('all')" ::icon="filter === 'all' ? 'check' : ''">
                                        All
                                    </flux:menu.item>
                                    <flux:menu.item @click="setFilter('unread')" ::icon="filter === 'unread' ? 'check' : ''">
                                        Unreads only
                                    </flux:menu.item>
                                    <flux:menu.separator />
                                    <div class="px-2 py-1 text-[11px] font-semibold uppercase tracking-wider text-zinc-500">
                                        Sort by
                                    </div>
                                    <flux:menu.item @click="setSort('alpha')" ::icon="sort === 'alpha' ? 'check' : ''">
                                        A–Z
                                    </flux:menu.item>
                                    <flux:menu.item @click="setSort('recent')" ::icon="sort === 'recent' ? 'check' : ''">
                                        Recency
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                            <button type="button"
                                    title="New message"
                                    @click="$dispatch('modal-show', { name: 'new-message' })"
                                    class="rounded p-1 hover:bg-zinc-200/60 hover:text-zinc-900
                                           dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                <flux:icon name="pencil-square" class="size-4" />
                            </button>
                            <button type="button"
                                    title="Collapse sidebar"
                                    @click="sidebarOpen = false"
                                    class="rounded p-1 hover:bg-zinc-200/60 hover:text-zinc-900
                                           dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                <flux:icon name="chevron-double-left" class="size-4" />
                            </button>
                        </div>
                    </div>

                    <livewire:bonfire::rooms />

                    @php
                        $authUser = auth()->user();
                        $authMember = $authUser ? \ArtisanBuild\Bonfire\Facades\Bonfire::memberFor($authUser) : null;
                        $authName = $authMember?->display_name ?? $authUser?->name ?? 'Me';
                        $authAvatar = $authMember?->avatar_url ?? ('https://ui-avatars.com/api/?name='.urlencode($authName));
                    @endphp

                    <div class="flex-shrink-0 border-t border-zinc-200 px-2 py-2
                                dark:border-zinc-800">
                        <flux:dropdown position="top" align="start">
                            <button type="button"
                                    class="group flex w-full items-center gap-2 rounded px-2 py-1.5 text-left
                                           hover:bg-zinc-200/60
                                           dark:hover:bg-zinc-800">
                                <span class="relative flex-shrink-0">
                                    <img src="{{ $authAvatar }}" alt=""
                                         class="size-7 rounded bg-zinc-200 dark:bg-zinc-700">
                                    <span class="absolute -right-0.5 -bottom-0.5 size-2.5 rounded-full border-2 border-zinc-50
                                                 bg-emerald-500 dark:border-zinc-900"></span>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <div class="truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                        {{ $authName }}
                                    </div>
                                    <div class="truncate text-xs text-emerald-600 dark:text-emerald-400">
                                        Active
                                    </div>
                                </div>
                                <flux:icon name="chevron-up" class="size-3.5 text-zinc-400" />
                            </button>

                            <flux:menu>
                                <flux:menu.item icon="user">Profile</flux:menu.item>
                                <flux:menu.item icon="face-smile">Set a status</flux:menu.item>
                                <flux:menu.item icon="moon">Set yourself away</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="cog-6-tooth" href="{{ route('profile.edit', absolute: false) }}" wire:navigate>
                                    Preferences
                                </flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="arrow-right-start-on-rectangle"
                                                href="{{ route('logout', absolute: false) }}" wire:navigate>
                                    Sign out of {{ config('app.name') }}
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>

                <div @mousedown.prevent="startResize($event)"
                     @dblclick="sidebarWidth = 260"
                     class="absolute right-0 top-0 z-10 h-full w-1 cursor-col-resize
                            bg-transparent hover:bg-sky-500/60"
                     :class="resizing ? 'bg-sky-500' : ''"
                     title="Drag to resize · Double-click to reset"></div>
            </aside>

            <button type="button"
                    x-show="! sidebarOpen"
                    x-transition:enter="transition-opacity duration-200 delay-150"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    @click="sidebarOpen = true"
                    title="Show sidebar"
                    class="flex h-12 w-8 flex-shrink-0 items-center justify-center border-r border-zinc-200
                           bg-zinc-50 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900
                           dark:border-zinc-800 dark:bg-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                <flux:icon name="chevron-double-right" class="size-4" />
            </button>

            <main class="flex h-full min-w-0 flex-1 flex-col bg-white
                         dark:bg-zinc-950">
                {{ $slot }}
            </main>
        </div>

        @fluxScripts
    </body>
</html>
