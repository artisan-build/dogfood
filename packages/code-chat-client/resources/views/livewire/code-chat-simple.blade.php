<div class="chat-container">
    {{-- Messages --}}
    @forelse($messages as $message)
        <div class="message message-{{ $message['role'] }}">
            <strong>{{ ucfirst($message['role']) }}:</strong>
            {{ $message['content'] }}
        </div>
    @empty
        <div class="empty-state">
            <p>Start a conversation by typing a message below.</p>
        </div>
    @endforelse

    {{-- Input --}}
    <form wire:submit="sendMessage">
        <input 
            type="text"
            wire:model="message" 
            placeholder="{{ $placeholder }}"
            @if($streaming) disabled @endif
        />
        <button type="submit" @if($streaming || empty($message)) disabled @endif>
            {{ $sendButtonText }}
        </button>
    </form>
</div>