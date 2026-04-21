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
                    @php
                        $currentBonfireMember = \ArtisanBuild\Bonfire\Facades\Bonfire::memberFor(auth()->user());
                        $isBonfireAdmin = $currentBonfireMember?->hasRoleAtLeast(\ArtisanBuild\Bonfire\Enums\BonfireRole::Admin) ?? false;
                    @endphp

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
                                @if ($isBonfireAdmin)
                                    <flux:menu.item icon="cog-6-tooth"
                                                    href="{{ route('bonfire.admin.index') }}"
                                                    wire:navigate>
                                        Workspace settings
                                    </flux:menu.item>
                                    <flux:menu.item icon="shield-check"
                                                    href="{{ route('bonfire.admin.index') }}"
                                                    wire:navigate>
                                        Admin panel
                                    </flux:menu.item>
                                @else
                                    <flux:menu.item icon="cog-6-tooth" disabled>Workspace settings</flux:menu.item>
                                @endif
                                <flux:menu.separator />
                                <flux:menu.item icon="arrow-right-start-on-rectangle"
                                                @click="document.getElementById('bonfire-logout-form').submit()">
                                    Sign out
                                </flux:menu.item>
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
                                     // Explicit sort overrides manual drag order
                                     localStorage.removeItem('bonfire.channel.order');
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
                                    <button type="button" @click="setFilter('all')"
                                            class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-left text-sm
                                                   hover:bg-zinc-100 dark:text-zinc-100 dark:hover:bg-zinc-800">
                                        <flux:icon name="check" class="size-4 text-sky-600"
                                                   ::class="filter === 'all' ? '' : 'invisible'" />
                                        All
                                    </button>
                                    <button type="button" @click="setFilter('unread')"
                                            class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-left text-sm
                                                   hover:bg-zinc-100 dark:text-zinc-100 dark:hover:bg-zinc-800">
                                        <flux:icon name="check" class="size-4 text-sky-600"
                                                   ::class="filter === 'unread' ? '' : 'invisible'" />
                                        Unreads only
                                    </button>
                                    <flux:menu.separator />
                                    <div class="px-2 py-1 text-[11px] font-semibold uppercase tracking-wider text-zinc-500">
                                        Sort by
                                    </div>
                                    <button type="button" @click="setSort('alpha')"
                                            class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-left text-sm
                                                   hover:bg-zinc-100 dark:text-zinc-100 dark:hover:bg-zinc-800">
                                        <flux:icon name="check" class="size-4 text-sky-600"
                                                   ::class="sort === 'alpha' ? '' : 'invisible'" />
                                        A–Z
                                    </button>
                                    <button type="button" @click="setSort('recent')"
                                            class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-left text-sm
                                                   hover:bg-zinc-100 dark:text-zinc-100 dark:hover:bg-zinc-800">
                                        <flux:icon name="check" class="size-4 text-sky-600"
                                                   ::class="sort === 'recent' ? '' : 'invisible'" />
                                        Recency
                                    </button>
                                </flux:menu>
                            </flux:dropdown>
                            <button type="button"
                                    title="New message"
                                    @click="$dispatch('modal-show', { name: 'new-message' })"
                                    class="rounded p-1 hover:bg-zinc-200/60 hover:text-zinc-900
                                           dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                <flux:icon name="pencil-square" class="size-4" />
                            </button>
                            @if ($isBonfireAdmin)
                                <a href="{{ route('bonfire.admin.index') }}"
                                   wire:navigate
                                   title="Admin panel"
                                   class="rounded p-1 hover:bg-zinc-200/60 hover:text-zinc-900
                                          dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                    <flux:icon name="shield-check" class="size-4" />
                                </a>
                            @endif
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

                    @auth
                        <livewire:bonfire::user-footer />
                    @endauth
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

        @auth
            <form id="bonfire-logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>

            <livewire:bonfire::call-panel />

            <div x-data="{
                     toasts: [],
                     push(payload) {
                         const id = Date.now() + Math.random();
                         this.toasts.push({ id, ...payload });
                         setTimeout(() => this.toasts = this.toasts.filter(t => t.id !== id), 6000);
                     },
                     init() {
                         if (typeof window.Echo === 'undefined') return;
                         window.Echo.private('App.Models.User.{{ auth()->id() }}')
                             .listen('.member.mentioned', (e) => {
                                 this.push({
                                     author_name: e.author_name,
                                     author_avatar: e.author_avatar,
                                     room_name: e.room_name,
                                     room_slug: e.room_slug,
                                     preview: e.preview,
                                 });
                             });
                     },
                 }"
                 class="pointer-events-none fixed bottom-4 right-4 z-50 flex w-80 flex-col gap-2">
                <template x-for="toast in toasts" :key="toast.id">
                    <a :href="'{{ url(config('bonfire.route_prefix', 'bonfire')) }}/' + toast.room_slug"
                       x-transition:enter="transition duration-200"
                       x-transition:enter-start="opacity-0 translate-x-4"
                       x-transition:enter-end="opacity-100 translate-x-0"
                       x-transition:leave="transition duration-150"
                       x-transition:leave-start="opacity-100"
                       x-transition:leave-end="opacity-0"
                       class="pointer-events-auto flex gap-3 rounded-lg border border-zinc-200 bg-white p-3
                              shadow-lg
                              dark:border-zinc-700 dark:bg-zinc-900">
                        <img :src="toast.author_avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(toast.author_name)"
                             alt="" class="size-9 flex-shrink-0 rounded bg-zinc-200 dark:bg-zinc-700">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-baseline justify-between gap-2">
                                <span class="truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100"
                                      x-text="toast.author_name"></span>
                                <span class="flex-shrink-0 text-[11px] text-zinc-500"
                                      x-text="'#' + toast.room_name"></span>
                            </div>
                            <p class="mt-0.5 line-clamp-2 text-xs text-zinc-600 dark:text-zinc-400"
                               x-text="'Mentioned you: ' + toast.preview"></p>
                        </div>
                    </a>
                </template>
            </div>
        @endauth

        @fluxScripts
    </body>
</html>
