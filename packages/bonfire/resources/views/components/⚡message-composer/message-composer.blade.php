<form wire:submit="send"
      x-data="{
          lastWhisper: 0,
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
          }
      }"
      class="flex flex-col gap-2">
    <textarea wire:model="body"
              rows="3"
              placeholder="Write a message..."
              @keydown.enter.exact.prevent="$wire.send()"
              @input.debounce.250ms="whisperTyping()"
              class="block w-full resize-none rounded-md border border-zinc-300 bg-white p-2 text-sm
                     focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500
                     dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"></textarea>
    <div class="flex items-center justify-between">
        <span class="text-xs text-zinc-500 dark:text-zinc-400">
            Shift+&crarr; for new line
        </span>
        <flux:button type="submit" variant="primary" size="sm">Send</flux:button>
    </div>
</form>
