<div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">
    {{-- Session Sidebar --}}
    <div class="w-80 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col">
        {{-- Sidebar Header --}}
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <flux:heading size="lg" class="mb-3">Sessions</flux:heading>
            <flux:button
                wire:click="createNewSession"
                variant="primary"
                class="w-full">
                <flux:icon.plus class="w-4 h-4 mr-2" />
                New Session
            </flux:button>
        </div>

        {{-- Session List --}}
        <div class="flex-1 overflow-y-auto p-2">
            @if ($loadingSessions)
                <div class="p-4 text-center text-gray-500">
                    <flux:icon.arrow-path class="w-6 h-6 mx-auto mb-2 animate-spin" />
                    Loading sessions...
                </div>
            @elseif (empty($sessions))
                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                    <flux:icon.chat-bubble-left class="w-12 h-12 mx-auto mb-2 opacity-50" />
                    <p class="text-sm">No sessions yet</p>
                    <p class="text-xs">Create a session to start chatting</p>
                </div>
            @else
                @foreach ($sessions as $session)
                    @php
                        $isChild = isset($session['parent_id']) && $session['parent_id'];
                        $indentClass = $isChild ? 'ml-6' : '';
                    @endphp

                    <div
                        wire:key="session-{{ $session['id'] }}"
                        class="mb-2 p-3 rounded-lg cursor-pointer transition-colors {{ $indentClass }}
                               {{ $currentSessionId === $session['id']
                                   ? 'bg-blue-100 dark:bg-blue-900 border-2 border-blue-500'
                                   : 'bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600' }}"
                        wire:click="switchSession('{{ $session['id'] }}')">

                        {{-- Session Info --}}
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                @if ($currentSessionId === $session['id'])
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                        <flux:badge color="blue" size="sm">Active</flux:badge>
                                    </div>
                                @endif

                                <div class="flex items-center gap-2">
                                    {{-- Fork indicator for child sessions --}}
                                    @if ($isChild)
                                        <flux:icon.code-bracket class="w-3 h-3 text-purple-500" />
                                    @endif

                                    <flux:heading
                                        size="sm"
                                        class="truncate {{ $currentSessionId === $session['id'] ? 'font-bold' : '' }}">
                                        {{ $session['name'] ?? 'Session '.$session['id'] }}
                                    </flux:heading>
                                </div>

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @if ($isChild)
                                        <span class="text-purple-600 dark:text-purple-400">Forked</span> ·
                                    @endif
                                    Created {{ isset($session['created_at']) ? \Carbon\Carbon::parse($session['created_at'])->diffForHumans() : 'recently' }}
                                </p>
                            </div>

                            {{-- Session Actions --}}
                            <div class="flex gap-1 ml-2">
                                <flux:button
                                    wire:click.stop="deleteSession('{{ $session['id'] }}')"
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    title="Delete session" />
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Sidebar Footer --}}
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ count($sessions) }} {{ Str::plural('session', count($sessions)) }}
                </div>
                @if (count($sessions) > 0)
                    <flux:button
                        wire:click="openTreeModal"
                        size="sm"
                        variant="ghost">
                        View Tree
                    </flux:button>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Chat Area --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Chat Header --}}
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="lg">
                        OpenCode Chat
                    </flux:heading>
                    @if ($currentSessionId)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Session: {{ $currentSessionId }}
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    @if ($currentSessionId)
                        {{-- Permissions Indicator --}}
                        @if ($this->pendingPermissionCount > 0)
                            <div class="relative">
                                <flux:icon.shield-check class="w-5 h-5 text-blue-500 animate-pulse" />
                                <flux:badge
                                    color="blue"
                                    size="sm"
                                    class="absolute -top-2 -right-2">
                                    {{ $this->pendingPermissionCount }}
                                </flux:badge>
                            </div>
                        @endif

                        {{-- Todo Button --}}
                        <flux:button
                            wire:click="openTodoPanel"
                            variant="ghost"
                            size="sm"
                            class="relative">
                            <flux:icon.clipboard-document-list class="w-5 h-5" />
                            @if ($this->incompleteTodoCount > 0)
                                <flux:badge
                                    color="blue"
                                    size="sm"
                                    class="absolute -top-2 -right-2">
                                    {{ $this->incompleteTodoCount }}
                                </flux:badge>
                            @endif
                        </flux:button>

                        {{-- Actions Dropdown --}}
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm">
                                Actions
                                <flux:icon.chevron-down class="ml-1 w-4 h-4" />
                            </flux:button>

                            <flux:menu>
                                <flux:menu.item wire:click="abortSession">
                                    <flux:icon.x-circle class="w-4 h-4 mr-2" />
                                    Abort Session
                                </flux:menu.item>

                                <flux:menu.item wire:click="summarizeSession">
                                    <flux:icon.document-text class="w-4 h-4 mr-2" />
                                    Summarize
                                </flux:menu.item>

                                @if ($shareUrl)
                                    <flux:menu.item wire:click="unshareSession">
                                        <flux:icon.link-slash class="w-4 h-4 mr-2" />
                                        Unshare Session
                                    </flux:menu.item>
                                @else
                                    <flux:menu.item wire:click="shareSession">
                                        <flux:icon.link class="w-4 h-4 mr-2" />
                                        Share Session
                                    </flux:menu.item>
                                @endif
                            </flux:menu>
                        </flux:dropdown>

                        <flux:badge color="green">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            Connected
                        </flux:badge>
                    @endif
                </div>
            </div>

            {{-- Error/Success Messages --}}
            @if ($error)
                <div class="mt-3 p-3 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 rounded">
                    <div class="flex items-start">
                        <flux:icon.exclamation-circle class="w-5 h-5 mr-2 flex-shrink-0" />
                        <div class="flex-1">{{ $error }}</div>
                        <button
                            wire:click="$set('error', null)"
                            class="ml-2">
                            <flux:icon.x-mark class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            @endif

            @if ($successMessage)
                <div class="mt-3 p-3 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded">
                    <div class="flex items-start">
                        <flux:icon.check-circle class="w-5 h-5 mr-2 flex-shrink-0" />
                        <div class="flex-1">{{ $successMessage }}</div>
                        <button
                            wire:click="$set('successMessage', null)"
                            class="ml-2">
                            <flux:icon.x-mark class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            @endif

            {{-- Share URL Display --}}
            @if ($shareUrl)
                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded">
                    <div class="flex items-start gap-3">
                        <flux:icon.link class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-1">
                                Shareable Link
                            </div>
                            <div class="flex items-center gap-2">
                                <code class="flex-1 text-xs bg-white dark:bg-gray-800 px-2 py-1 rounded border border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-300 truncate">
                                    {{ $shareUrl }}
                                </code>
                                <flux:button
                                    onclick="navigator.clipboard.writeText('{{ $shareUrl }}'); this.textContent = 'Copied!'; setTimeout(() => this.textContent = 'Copy', 2000)"
                                    size="sm"
                                    variant="primary">
                                    Copy
                                </flux:button>
                            </div>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                Share this link to give others access to this session
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Messages Area --}}
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900" id="messages-container">
            @if (!$currentSessionId)
                <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                    <div class="text-center">
                        <flux:icon.chat-bubble-left-right class="w-16 h-16 mx-auto mb-4 opacity-50" />
                        <p class="text-lg mb-2">No active session</p>
                        <p class="text-sm">Select a session from the sidebar or create a new one to start chatting</p>
                    </div>
                </div>
            @elseif (empty($messages))
                <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                    <div class="text-center">
                        <flux:icon.chat-bubble-oval-left class="w-16 h-16 mx-auto mb-4 opacity-50" />
                        <p class="text-lg mb-2">Start the conversation</p>
                        <p class="text-sm">Type a message below to begin</p>
                    </div>
                </div>
            @else
                <div class="space-y-4 max-w-4xl mx-auto">
                    @foreach ($messages as $index => $message)
                        <x-opencode-client::message-bubble :message="$message" :index="$index" />
                    @endforeach

                    @if ($sending)
                        <div class="flex justify-start">
                            <div class="max-w-[70%] rounded-lg p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <div class="text-xs font-semibold mb-2 text-gray-500 dark:text-gray-400">Assistant</div>
                                <div class="flex items-center gap-2">
                                    <flux:icon.arrow-path class="w-4 h-4 animate-spin" />
                                    <span class="text-sm">Thinking...</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Message Input Section --}}
        <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4">
            <div class="max-w-4xl mx-auto">
                <div class="flex gap-3">
                    <flux:textarea
                        wire:model.live="messageInput"
                        wire:keydown.enter.prevent="sendMessage"
                        placeholder="Type your message... (Enter to send)"
                        rows="2"
                        class="flex-1"
                        :disabled="!$currentSessionId || $sending" />

                    <flux:button
                        wire:click="sendMessage"
                        variant="primary"
                        :disabled="!$currentSessionId || $sending || empty(trim($messageInput))">
                        @if ($sending)
                            <flux:icon.arrow-path class="w-4 h-4 mr-2 animate-spin" />
                            Sending
                        @else
                            <flux:icon.paper-airplane class="w-4 h-4 mr-2" />
                            Send
                        @endif
                    </flux:button>
                </div>

                @if (!$currentSessionId)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Create or select a session to start chatting
                    </p>
                @endif

                {{-- Shell Command Section --}}
                @if ($currentSessionId)
                    <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="mb-2">
                            <flux:label>
                                <flux:icon.command-line class="w-4 h-4 mr-1 inline" />
                                Shell Command
                            </flux:label>
                        </div>
                        <div class="flex gap-3">
                            <flux:input
                                class="shell-command-input flex-1"
                                wire:model.live="shellCommand"
                                wire:keydown.enter.prevent="executeShellCommand"
                                placeholder="Enter shell command... (Enter to execute)"
                                :disabled="$executingCommand" />

                            <flux:button
                                wire:click="executeShellCommand"
                                variant="primary"
                                :disabled="$executingCommand || empty(trim($shellCommand))">
                                @if ($executingCommand)
                                    <flux:icon.arrow-path class="w-4 h-4 mr-2 animate-spin" />
                                    Executing
                                @else
                                    <flux:icon.play class="w-4 h-4 mr-2" />
                                    Execute
                                @endif
                            </flux:button>
                        </div>

                        {{-- Shell Output --}}
                        @if ($shellOutput)
                            <div class="mt-3 bg-gray-900 dark:bg-black text-gray-100 p-4 rounded font-mono text-xs overflow-x-auto">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-green-400">$ Output</span>
                                    <flux:button
                                        wire:click="$set('shellOutput', null)"
                                        variant="ghost"
                                        size="xs">
                                        <flux:icon.x-mark class="w-3 h-3" />
                                    </flux:button>
                                </div>
                                <pre class="whitespace-pre-wrap">{{ $shellOutput }}</pre>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Diff Viewer Modal --}}
@if ($showDiffModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         wire:click="closeDiffModal">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] flex flex-col"
             wire:click.stop>
            {{-- Modal Header --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <flux:heading size="lg">Code Changes</flux:heading>
                <flux:button
                    wire:click="closeDiffModal"
                    variant="ghost"
                    icon="x-mark" />
            </div>

            {{-- Modal Body --}}
            <div class="p-6 flex-1 overflow-auto">
                @if (!$diffData || empty($diffData['files']))
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        <flux:icon.document-text class="w-12 h-12 mx-auto mb-2 opacity-50" />
                        <p>No diff available</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($diffData['files'] as $file)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                {{-- File Header --}}
                                <div class="bg-gray-100 dark:bg-gray-900 px-4 py-2 font-mono text-sm">
                                    {{ $file['path'] }}
                                </div>

                                {{-- Diff Content --}}
                                <div class="diff-viewer bg-white dark:bg-gray-800 p-4 font-mono text-xs overflow-x-auto">
                                    @foreach (explode("\n", $file['diff']) as $line)
                                        @php
                                            $lineClass = 'diff-line';
                                            if (str_starts_with($line, '+')) {
                                                $lineClass = 'diff-addition bg-green-50 dark:bg-green-900/20 text-green-900 dark:text-green-200';
                                            } elseif (str_starts_with($line, '-')) {
                                                $lineClass = 'diff-deletion bg-red-50 dark:bg-red-900/20 text-red-900 dark:text-red-200';
                                            }
                                        @endphp
                                        <div class="{{ $lineClass }} whitespace-pre">{{ $line }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

{{-- Todo Panel --}}
@if ($showTodoPanel)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50"
         wire:click="closeTodoPanel">
        <div class="todo-panel absolute right-0 top-0 h-full w-96 bg-white dark:bg-gray-800 shadow-2xl flex flex-col"
             wire:click.stop>
            {{-- Panel Header --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <flux:heading size="lg">Todos</flux:heading>
                <flux:button
                    wire:click="closeTodoPanel"
                    variant="ghost"
                    icon="x-mark" />
            </div>

            {{-- Panel Body --}}
            <div class="p-6 flex-1 overflow-auto">
                @if (empty($todos))
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        <flux:icon.clipboard-document-list class="w-12 h-12 mx-auto mb-2 opacity-50" />
                        <p>No todos yet</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($todos as $todo)
                            <div
                                wire:key="todo-{{ $todo['id'] }}"
                                class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700
                                       {{ $todo['completed'] ? 'bg-gray-50 dark:bg-gray-900' : 'bg-white dark:bg-gray-800' }}">
                                {{-- Checkbox --}}
                                <input
                                    type="checkbox"
                                    {{ $todo['completed'] ? 'checked' : '' }}
                                    wire:click="toggleTodo('{{ $todo['id'] }}')"
                                    class="mt-1 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">

                                {{-- Todo Content --}}
                                <div class="flex-1 {{ $todo['completed'] ? 'line-through text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $todo['content'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Panel Footer --}}
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-600 dark:text-gray-400 text-center">
                    {{ $this->incompleteTodoCount }} of {{ $this->todoCount }} incomplete
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Dangerous Command Confirmation Modal --}}
@if ($showDangerousCommandModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         wire:click="cancelDangerousCommand">
        <div class="dangerous-command-modal bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4"
             wire:click.stop>
            {{-- Modal Header --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <flux:icon.exclamation-triangle class="w-8 h-8 text-yellow-500" />
                    <flux:heading size="lg">Dangerous Command</flux:heading>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="p-6">
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    You're about to execute a potentially dangerous command:
                </p>
                <div class="bg-gray-100 dark:bg-gray-900 p-3 rounded font-mono text-sm text-red-600 dark:text-red-400 break-all">
                    {{ $pendingCommand }}
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-4">
                    This command could modify or delete files. Are you sure you want to proceed?
                </p>
            </div>

            {{-- Modal Footer --}}
            <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex gap-3 justify-end">
                <flux:button
                    wire:click="cancelDangerousCommand"
                    variant="ghost">
                    Cancel
                </flux:button>
                <flux:button
                    wire:click="confirmDangerousCommand"
                    variant="danger">
                    Execute Anyway
                </flux:button>
            </div>
        </div>
    </div>
@endif

{{-- Permission Request Modal --}}
@if (count($pendingPermissions) > 0)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="permission-modal bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] flex flex-col">
            {{-- Modal Header --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <flux:icon.shield-check class="w-8 h-8 text-blue-500" />
                    <div>
                        <flux:heading size="lg">Permission Requests</flux:heading>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ count($pendingPermissions) }} {{ Str::plural('request', count($pendingPermissions)) }} pending
                        </p>
                    </div>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 flex-1 overflow-auto">
                <div class="space-y-4">
                    @foreach ($pendingPermissions as $permission)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <flux:badge
                                            size="sm"
                                            variant="primary">
                                            {{ $permission['type'] }}
                                        </flux:badge>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $permission['id'] }}
                                        </span>
                                    </div>
                                    <div class="font-mono text-sm text-gray-900 dark:text-gray-100 break-all bg-white dark:bg-black p-2 rounded">
                                        {{ $permission['resource'] }}
                                    </div>
                                    @if (isset($permission['reason']))
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                            {{ $permission['reason'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex gap-2 justify-end">
                                <flux:button
                                    wire:click="denyPermission('{{ $permission['id'] }}')"
                                    variant="ghost"
                                    size="sm">
                                    <flux:icon.x-mark class="w-4 h-4 mr-1" />
                                    Deny
                                </flux:button>
                                <flux:button
                                    wire:click="approvePermission('{{ $permission['id'] }}')"
                                    variant="primary"
                                    size="sm">
                                    <flux:icon.check class="w-4 h-4 mr-1" />
                                    Approve
                                </flux:button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Session Tree Modal --}}
@if ($showTreeModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         wire:click="closeTreeModal">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[80vh] flex flex-col"
             wire:click.stop>
            {{-- Modal Header --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <flux:heading size="lg">Session Tree</flux:heading>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ count($sessions) }} {{ Str::plural('session', count($sessions)) }}
                    </p>
                </div>
                <flux:button
                    wire:click="closeTreeModal"
                    variant="ghost"
                    icon="x-mark" />
            </div>

            {{-- Modal Body --}}
            <div class="p-6 flex-1 overflow-auto">
                @if (empty($sessions))
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        <flux:icon.chat-bubble-left class="w-12 h-12 mx-auto mb-2 opacity-50" />
                        <p>No sessions to display</p>
                    </div>
                @else
                    <div id="session-tree" class="w-full h-96 border border-gray-200 dark:border-gray-700 rounded"></div>
                @endif
            </div>
        </div>
    </div>
@endif

@script
<script>
    // Auto-scroll to bottom when new messages arrive
    $wire.on('message-sent', () => {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });

    // Initialize tree visualization when modal opens
    $wire.on('tree-modal-opened', () => {
        // Vis-network initialization will go here
        const container = document.getElementById('session-tree');
        if (container && window.vis) {
            const data = $wire.get('treeData');
            const nodes = new vis.DataSet(data.nodes);
            const edges = new vis.DataSet(data.edges);

            const network = new vis.Network(container, {
                nodes: nodes,
                edges: edges
            }, {
                layout: {
                    hierarchical: {
                        direction: 'UD',
                        sortMethod: 'directed'
                    }
                },
                edges: {
                    arrows: 'to'
                }
            });

            // Handle node click
            network.on('click', function(params) {
                if (params.nodes.length > 0) {
                    const sessionId = params.nodes[0];
                    $wire.call('navigateToSessionFromTree', sessionId);
                }
            });
        }
    });
</script>
@endscript
