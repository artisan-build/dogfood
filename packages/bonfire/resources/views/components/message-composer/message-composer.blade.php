<div x-data="{
          lastWhisper: 0,
          showEmoji: false,
          showMentions: false,
          mentionQuery: '',
          emojis: ['👍','👏','🙏','🎉','🔥','🚀','💯','✅','❌','⭐','❤️','💙','😂','😅','😊','😢','😮','😡','🤔','👀','🙌','🤝','☕','🥳'],

          recording: null,
          recordKind: null,
          recordStartedAt: null,
          recordElapsed: 0,
          recordTimer: null,

          scheduleOpen: false,
          scheduledFor: @entangle('scheduledFor'),
          scheduleCustom: '',

          init() {
              const root = this.$root;
              let lastEnterAt = 0;
              let firstEnterWasInList = false;
              const getEditor = () => {
                  const el = root.querySelector('[data-flux-editor]');
                  return el?._tiptap || el?.editor || null;
              };
              const handleShortcut = (e) => {
                  if (! root.contains(e.target)) return;
                  if (! (e.target.matches('[contenteditable=true]') || e.target.closest('[contenteditable=true]'))) return;
                  if (e.key !== 'Enter' || e.shiftKey || e.isComposing) return;

                  const now = Date.now();
                  const doubleTap = now - lastEnterAt < 250;

                  if (doubleTap) {
                      e.preventDefault();
                      e.stopPropagation();
                      if (firstEnterWasInList) {
                          getEditor()?.chain().focus().undo().run();
                      }
                      this.submitForm();
                      lastEnterAt = 0;
                      firstEnterWasInList = false;
                      return;
                  }

                  const editor = getEditor();
                  const inList = editor && (editor.isActive('bulletList') || editor.isActive('orderedList') || editor.isActive('listItem'));
                  lastEnterAt = now;
                  firstEnterWasInList = !! inList;

                  if (inList) return;

                  e.preventDefault();
                  e.stopPropagation();
                  this.submitForm();
              };
              document.addEventListener('keydown', handleShortcut, true);
          },

          submitForm() {
              this.showMentions = false;
              this.showEmoji = false;
              const form = this.$refs.form;
              if (form) form.requestSubmit();
          },

          whisperTyping() {
              if (typeof window.Echo === 'undefined') return;
              const now = Date.now();
              if (now - this.lastWhisper < 1500) return;
              this.lastWhisper = now;
              window.Echo.join('bonfire.room.{{ $room->id }}')
                  .whisper('user.typing', {
                      member_id: {{ $this->member?->id ?? 0 }},
                      display_name: @js($this->member?->display_name ?? ''),
                  });
          },

          insertIntoEditor(text) {
              const el = this.$root.querySelector('[data-flux-editor]');
              const editor = el?._tiptap || el?.editor;
              if (editor) {
                  editor.chain().focus().insertContent(text).run();
              }
          },

          toggleEmoji() {
              this.showMentions = false;
              this.showEmoji = ! this.showEmoji;
          },
          pickEmoji(emoji) {
              this.insertIntoEditor(emoji);
              this.showEmoji = false;
          },

          openMentions() {
              this.showEmoji = false;
              this.mentionQuery = '';
              this.showMentions = true;
              this.insertIntoEditor('@');
          },
          selectMention(name) {
              this.insertIntoEditor(name + ' ');
              this.showMentions = false;
              this.mentionQuery = '';
          },
          get filteredMembers() {
              const q = (this.mentionQuery || '').toLowerCase();
              const all = @js($this->mentionables);
              if (q === '') return all.slice(0, 6);
              return all.filter(m => m.display_name.toLowerCase().includes(q)).slice(0, 6);
          },

          triggerFile() {
              this.$refs.fileInput?.click();
          },

          async startRecording(kind) {
              if (this.recording) return;
              if (! navigator.mediaDevices?.getUserMedia) {
                  alert('Your browser does not support recording.');
                  return;
              }
              try {
                  const constraints = kind === 'video'
                      ? { audio: true, video: { width: 640, height: 480 } }
                      : { audio: true };
                  const stream = await navigator.mediaDevices.getUserMedia(constraints);
                  const mimeType = kind === 'video'
                      ? (MediaRecorder.isTypeSupported('video/webm;codecs=vp8,opus') ? 'video/webm;codecs=vp8,opus' : 'video/webm')
                      : (MediaRecorder.isTypeSupported('audio/webm;codecs=opus') ? 'audio/webm;codecs=opus' : 'audio/webm');
                  const recorder = new MediaRecorder(stream, { mimeType });
                  const chunks = [];
                  recorder.addEventListener('dataavailable', (e) => { if (e.data.size) chunks.push(e.data); });
                  recorder.addEventListener('stop', () => {
                      stream.getTracks().forEach(t => t.stop());
                      const blob = new Blob(chunks, { type: mimeType });
                      const ext = kind === 'video' ? 'webm' : 'webm';
                      const filename = `${kind}-${Date.now()}.${ext}`;
                      const file = new File([blob], filename, { type: mimeType });
                      this.$wire.upload('pendingAttachments', file, null, null, null, 'pendingAttachments.' + (this.$wire.pendingAttachments?.length ?? 0));
                      this.recording = null;
                      this.recordKind = null;
                      this.recordStartedAt = null;
                      this.recordElapsed = 0;
                      clearInterval(this.recordTimer);
                  });
                  this.recording = recorder;
                  this.recordKind = kind;
                  this.recordStartedAt = Date.now();
                  this.recordElapsed = 0;
                  this.recordTimer = setInterval(() => {
                      this.recordElapsed = Math.floor((Date.now() - this.recordStartedAt) / 1000);
                      if (this.recordElapsed >= 120) this.stopRecording();
                  }, 500);
                  recorder.start();
              } catch (err) {
                  console.error(err);
                  alert('Could not start recording: ' + err.message);
              }
          },

          stopRecording() {
              if (this.recording && this.recording.state !== 'inactive') {
                  this.recording.stop();
              }
          },

          recordLabel() {
              const s = this.recordElapsed % 60;
              const m = Math.floor(this.recordElapsed / 60);
              return `${m}:${s.toString().padStart(2, '0')}`;
          },

          chooseSchedule(value) {
              this.scheduledFor = value;
              this.scheduleOpen = false;
          },
          clearSchedule() {
              this.scheduledFor = null;
              this.scheduleCustom = '';
              this.scheduleOpen = false;
          },
          applyCustomSchedule() {
              if (! this.scheduleCustom) return;
              this.scheduledFor = this.scheduleCustom;
              this.scheduleOpen = false;
          },
          get scheduleLabel() {
              if (! this.scheduledFor) return '';
              try {
                  const d = new Date(this.scheduledFor);
                  return d.toLocaleString(undefined, { weekday: 'short', hour: 'numeric', minute: '2-digit' });
              } catch (e) {
                  return this.scheduledFor;
              }
          },
          get presetTimes() {
              const now = new Date();
              const in1h = new Date(now.getTime() + 60 * 60 * 1000);
              const tmr = new Date(now); tmr.setDate(tmr.getDate() + 1); tmr.setHours(9, 0, 0, 0);
              const nextMon = new Date(now);
              const day = nextMon.getDay();
              const add = (8 - day) % 7 || 7;
              nextMon.setDate(nextMon.getDate() + add); nextMon.setHours(9, 0, 0, 0);
              const fmt = (d) => d.toISOString().slice(0, 16);
              return [
                  { label: 'In 1 hour', value: fmt(in1h) },
                  { label: 'Tomorrow at 9:00 AM', value: fmt(tmr) },
                  { label: 'Next Monday at 9:00 AM', value: fmt(nextMon) },
              ];
          },
      }"
      @click.outside="showEmoji = false; showMentions = false; scheduleOpen = false"
      class="bonfire-composer relative">

    <form wire:submit="send"
          x-ref="form"
          class="flex flex-col gap-2">

        @if ($this->pendingAttachments)
            <div class="flex flex-wrap gap-2 rounded-md border border-zinc-200 p-2
                        dark:border-zinc-700">
                @foreach ($this->pendingAttachments as $index => $pending)
                    <div class="flex items-center gap-2 rounded-md border border-zinc-200 px-2 py-1 text-xs
                                dark:border-zinc-700">
                        <flux:icon name="paper-clip" class="size-3.5 text-zinc-500" />
                        <span class="max-w-40 truncate text-zinc-800 dark:text-zinc-200">
                            {{ $pending->getClientOriginalName() }}
                        </span>
                        <button type="button"
                                wire:click="removeAttachment({{ $index }})"
                                class="text-zinc-500 hover:text-rose-600 dark:hover:text-rose-400">
                            <flux:icon name="x-mark" class="size-3.5" />
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <div x-show="recording"
             class="flex items-center gap-3 rounded-md bg-rose-50 px-3 py-2 text-sm text-rose-700
                    dark:bg-rose-950/50 dark:text-rose-300">
            <span class="relative flex size-2">
                <span class="absolute inline-flex size-full animate-ping rounded-full bg-rose-400 opacity-75"></span>
                <span class="relative inline-flex size-2 rounded-full bg-rose-500"></span>
            </span>
            <span>Recording <span x-text="recordKind"></span> — <span x-text="recordLabel()"></span></span>
            <button type="button"
                    @click="stopRecording()"
                    class="ml-auto rounded bg-rose-600 px-2 py-0.5 text-xs font-semibold text-white
                           hover:bg-rose-700">
                Stop
            </button>
        </div>

        <flux:editor wire:model="body"
                     @input.debounce.250ms="whisperTyping()"
                     toolbar="bold italic strike | bullet ordered blockquote | link | code" />

        @if ($this->parentId !== null)
            <label class="flex items-center gap-2 px-1 text-xs text-zinc-600 dark:text-zinc-400">
                <input type="checkbox" wire:model="alsoSendToChannel"
                       class="size-3.5 rounded border-zinc-300 text-sky-600
                              focus:ring-sky-500
                              dark:border-zinc-600 dark:bg-zinc-900" />
                Also send to <span class="font-semibold">#{{ $room->name }}</span>
            </label>
        @endif

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-0.5 text-zinc-500 dark:text-zinc-400">
                <input type="file"
                       wire:model="pendingAttachments"
                       x-ref="fileInput"
                       multiple
                       class="hidden" />

                <flux:dropdown>
                    <flux:button type="button" variant="ghost" size="sm" icon="paper-clip" title="Attach">
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item icon="arrow-up-tray" @click="triggerFile()">
                            Upload from computer
                        </flux:menu.item>
                        <flux:menu.item icon="clock" disabled>
                            Recent files
                        </flux:menu.item>
                        <flux:menu.item icon="list-bullet" disabled>
                            From list
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>

                <button type="button"
                        title="Record voice note"
                        @click="recording ? stopRecording() : startRecording('audio')"
                        :class="recordKind === 'audio' ? 'text-rose-600 dark:text-rose-400' : ''"
                        class="rounded p-1.5 hover:bg-zinc-100 hover:text-zinc-900
                               dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                    <flux:icon name="microphone" class="size-4" />
                </button>
                <button type="button"
                        title="Record video clip"
                        @click="recording ? stopRecording() : startRecording('video')"
                        :class="recordKind === 'video' ? 'text-rose-600 dark:text-rose-400' : ''"
                        class="rounded p-1.5 hover:bg-zinc-100 hover:text-zinc-900
                               dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                    <flux:icon name="video-camera" class="size-4" />
                </button>

                <span class="mx-1 h-5 w-px bg-zinc-200 dark:bg-zinc-700"></span>

                <button type="button"
                        title="Emoji"
                        @mousedown.prevent @click.stop="toggleEmoji()"
                        class="rounded p-1.5 hover:bg-zinc-100 hover:text-zinc-900
                               dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                    <flux:icon name="face-smile" class="size-4" />
                </button>
                <button type="button"
                        title="Mention"
                        @mousedown.prevent @click="openMentions()"
                        class="rounded p-1.5 hover:bg-zinc-100 hover:text-zinc-900
                               dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                    <flux:icon name="at-symbol" class="size-4" />
                </button>
            </div>

            <div class="flex items-center gap-2">
                <span x-show="scheduledFor"
                      class="inline-flex items-center gap-1 rounded-md bg-sky-100 px-2 py-1 text-xs text-sky-800
                             dark:bg-sky-950/50 dark:text-sky-300">
                    <flux:icon name="clock" class="size-3.5" />
                    Scheduled: <span x-text="scheduleLabel"></span>
                    <button type="button" @click="clearSchedule()" class="hover:text-sky-950 dark:hover:text-sky-100">
                        <flux:icon name="x-mark" class="size-3.5" />
                    </button>
                </span>


                <div class="flex items-stretch overflow-hidden rounded-md">
                    <flux:button type="submit" variant="primary" size="sm" icon="paper-airplane">
                        <span x-text="scheduledFor ? 'Schedule' : 'Send'"></span>
                    </flux:button>
                    <div class="relative">
                        <button type="button"
                                @click.stop="scheduleOpen = ! scheduleOpen"
                                title="Schedule for later"
                                class="flex h-full items-center border-l border-white/20 bg-zinc-800 px-1.5 text-white
                                       hover:bg-zinc-700
                                       dark:border-black/20">
                            <flux:icon name="chevron-down" class="size-3.5" />
                        </button>

                        <div x-show="scheduleOpen"
                             x-transition.opacity.duration.100ms
                             @click.stop
                             class="absolute bottom-full right-0 z-20 mb-1 w-72 overflow-hidden rounded-lg border border-zinc-200
                                    bg-white shadow-lg
                                    dark:border-zinc-700 dark:bg-zinc-900"
                             style="display: none;">
                            <div class="border-b border-zinc-200 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-wider text-zinc-500
                                        dark:border-zinc-800 dark:text-zinc-400">
                                Schedule for later
                            </div>
                            <ul class="py-1">
                                <template x-for="preset in presetTimes" :key="preset.value">
                                    <li>
                                        <button type="button"
                                                @click="chooseSchedule(preset.value)"
                                                class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-sm
                                                       hover:bg-zinc-100
                                                       dark:hover:bg-zinc-800">
                                            <flux:icon name="clock" class="size-3.5 text-zinc-500" />
                                            <span x-text="preset.label"></span>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                            <div class="border-t border-zinc-200 p-3 dark:border-zinc-800">
                                <label class="block text-[11px] font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                    Custom time
                                </label>
                                <div class="mt-1 flex items-center gap-2">
                                    <input type="datetime-local"
                                           x-model="scheduleCustom"
                                           class="flex-1 rounded border border-zinc-300 bg-white px-2 py-1 text-sm
                                                  focus:border-zinc-500 focus:outline-none
                                                  dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100" />
                                    <button type="button"
                                            @click="applyCustomSchedule()"
                                            class="rounded bg-zinc-900 px-2 py-1 text-sm text-white hover:bg-zinc-700
                                                   dark:bg-white dark:text-zinc-900">
                                        Set
                                    </button>
                                </div>
                                <button x-show="scheduledFor"
                                        type="button"
                                        @click="clearSchedule()"
                                        class="mt-2 text-xs text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100">
                                    Clear schedule
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div x-show="showEmoji"
         x-transition.opacity.duration.100ms
         @click.stop
         class="absolute bottom-14 left-12 z-20 grid grid-cols-6 gap-1 rounded-lg border border-zinc-200
                bg-white p-2 shadow-lg
                dark:border-zinc-700 dark:bg-zinc-900"
         style="display: none;">
        <template x-for="emoji in emojis" :key="emoji">
            <button type="button"
                    @mousedown.prevent @click="pickEmoji(emoji)"
                    class="size-8 rounded text-lg hover:bg-zinc-100
                           dark:hover:bg-zinc-800"
                    x-text="emoji"></button>
        </template>
    </div>

    <div x-show="showMentions"
         x-transition.opacity.duration.100ms
         @click.stop
         class="absolute bottom-14 left-4 z-20 w-64 overflow-hidden rounded-lg border border-zinc-200
                bg-white shadow-lg
                dark:border-zinc-700 dark:bg-zinc-900"
         style="display: none;">
        <div class="border-b border-zinc-200 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-wider text-zinc-500
                    dark:border-zinc-800 dark:text-zinc-400">
            People
        </div>
        <ul class="max-h-60 overflow-y-auto py-1">
            <template x-for="member in filteredMembers" :key="member.id">
                <li>
                    <button type="button"
                            @mousedown.prevent @click="selectMention(member.display_name)"
                            class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-sm
                                   hover:bg-zinc-100
                                   dark:hover:bg-zinc-800">
                        <img :src="member.avatar_url" alt="" class="size-6 rounded bg-zinc-200 dark:bg-zinc-800">
                        <span x-text="member.display_name" class="truncate"></span>
                    </button>
                </li>
            </template>
            <template x-if="filteredMembers.length === 0">
                <li class="px-3 py-2 text-xs text-zinc-500 dark:text-zinc-400">No matches</li>
            </template>
        </ul>
    </div>
</div>
