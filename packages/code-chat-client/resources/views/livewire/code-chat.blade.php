<div class="flex flex-col h-full">
    {{-- Driver Selector --}}
    @if($showDriverSelector && count($this->availableDrivers) > 1)
        <div class="mb-4">
            <flux:select wire:model.live="driver" wire:change="changeDriver">
                @foreach($this->availableDrivers as $key => $name)
                    <option value="{{ $key }}">{{ $name }}</option>
                @endforeach
            </flux:select>
        </div>
    @endif

    {{-- Messages Container --}}
    <div 
        class="flex-1 overflow-y-auto space-y-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg"
        style="max-height: {{ $maxHeight }}px"
        x-data
        x-init="$el.scrollTop = $el.scrollHeight"
        x-on:message-updated.window="$nextTick(() => $el.scrollTop = $el.scrollHeight)"
    >
        @forelse($messages as $message)
            <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%]">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                        {{ ucfirst($message['role']) }}
                    </div>
                    <flux:card 
                        class="{{ $message['role'] === 'user' ? 'bg-blue-50 dark:bg-blue-900/20' : ($message['role'] === 'error' ? 'bg-red-50 dark:bg-red-900/20' : 'bg-white dark:bg-gray-800') }}"
                    >
                        <div class="prose dark:prose-invert max-w-none">
                            @if($message['role'] === 'assistant' && $streaming && $loop->last)
                                <div class="whitespace-pre-wrap">{!! nl2br(e($message['content'])) !!}</div>
                                <flux:spinner size="sm" class="ml-2 inline-block" />
                            @else
                                <div class="whitespace-pre-wrap">{!! nl2br(e($message['content'])) !!}</div>
                            @endif
                            
                            @if(isset($message['metadata']['tool_usage']) && count($message['metadata']['tool_usage']) > 0)
                                <div class="mt-2 text-xs text-gray-500">
                                    <flux:badge variant="subtle">
                                        Used tools: {{ count($message['metadata']['tool_usage']) }}
                                    </flux:badge>
                                </div>
                            @endif
                        </div>
                    </flux:card>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 dark:text-gray-400">
                <flux:icon name="chat-bubble-left-right" class="w-12 h-12 mx-auto mb-2 opacity-20" />
                <p>Start a conversation by typing a message below.</p>
            </div>
        @endforelse
    </div>

    {{-- Input Area --}}
    <div class="mt-4">
        <form wire:submit="sendMessage" class="flex gap-2">
            <flux:input 
                wire:model="message" 
                placeholder="{{ $placeholder }}"
                class="flex-1"
                :disabled="$streaming"
                autofocus
            />
            <flux:button 
                type="submit" 
                variant="primary"
                :disabled="$streaming || empty(trim($message))"
            >
                @if($streaming)
                    <flux:spinner size="sm" />
                @else
                    {{ $sendButtonText }}
                @endif
            </flux:button>
            @if(count($messages) > 0)
                <flux:button 
                    wire:click="clearChat" 
                    variant="subtle"
                    wire:confirm="Are you sure you want to clear the chat history?"
                >
                    <flux:icon name="trash" class="w-4 h-4" />
                </flux:button>
            @endif
        </form>
    </div>
</div>