<div class="flex flex-col h-screen bg-gray-50 dark:bg-gray-900">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="2xl">
                    TUI Remote Control
                </flux:heading>
                <p class="text-lg text-gray-600 dark:text-gray-400 mt-1">
                    Control your OpenCode TUI from the browser
                </p>
            </div>

            {{-- Connection Status Indicator --}}
            <div class="connection-status flex items-center gap-3">
                @if ($tuiConnected)
                    <div class="flex items-center gap-2 px-4 py-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-lg font-semibold text-green-800 dark:text-green-200">
                            Connected
                        </span>
                    </div>
                @else
                    <div class="flex items-center gap-2 px-4 py-2 bg-red-100 dark:bg-red-900 rounded-lg">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-lg font-semibold text-red-800 dark:text-red-200">
                            Disconnected
                        </span>
                    </div>
                @endif

                <flux:button
                    wire:click="checkTuiConnection"
                    variant="ghost"
                    size="sm">
                    <flux:icon.arrow-path class="w-5 h-5" />
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Error Messages --}}
    @if ($error || !$tuiConnected)
        <div class="mx-6 mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
            <div class="flex items-start gap-3">
                <flux:icon.exclamation-circle class="w-8 h-8 text-red-600 dark:text-red-400 flex-shrink-0 mt-1" />
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-red-800 dark:text-red-300 mb-2">
                        TUI is not running
                    </h3>
                    @if ($error)
                        <p class="text-lg text-red-700 dark:text-red-300 mb-3">
                            {{ $error }}
                        </p>
                    @endif
                    <div class="text-base text-red-700 dark:text-red-300 space-y-2">
                        <p class="font-medium">To use the TUI Remote Control:</p>
                        <ol class="list-decimal list-inside space-y-1 ml-4">
                            <li>Start the OpenCode TUI application</li>
                            <li>Ensure it's running on <code class="px-2 py-1 bg-red-100 dark:bg-red-900 rounded font-mono text-sm">{{ $serverUrl }}</code></li>
                            <li>Click the refresh button above to reconnect</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Content Sections --}}
    @if ($tuiConnected)
        <div class="remote-sections flex-1 overflow-auto p-6">
            <div class="max-w-6xl mx-auto space-y-6">
                {{-- Prompt Management Section --}}
                <div class="prompt-section bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <flux:heading size="xl" class="mb-4">
                        Prompt Management
                    </flux:heading>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                        Submit, append, and clear prompts in the TUI
                    </p>

                    <div class="space-y-6">
                        {{-- Submit Prompt --}}
                        <div>
                            <label class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Submit New Prompt
                            </label>
                            <div class="flex gap-3">
                                <flux:input
                                    wire:model="promptText"
                                    placeholder="Enter your prompt here..."
                                    class="flex-1 text-lg" />
                                <flux:button
                                    wire:click="submitPrompt"
                                    :disabled="$isSubmittingPrompt"
                                    variant="primary">
                                    @if ($isSubmittingPrompt)
                                        <flux:icon.arrow-path class="w-5 h-5 animate-spin" />
                                        Submitting...
                                    @else
                                        <flux:icon.paper-airplane class="w-5 h-5" />
                                        Submit
                                    @endif
                                </flux:button>
                            </div>
                        </div>

                        {{-- Append to Prompt --}}
                        <div>
                            <label class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Append to Existing Prompt
                            </label>
                            <div class="flex gap-3">
                                <flux:input
                                    wire:model="appendText"
                                    placeholder="Text to append..."
                                    class="flex-1 text-lg" />
                                <flux:button
                                    wire:click="appendPrompt"
                                    :disabled="$isAppendingPrompt"
                                    variant="ghost">
                                    @if ($isAppendingPrompt)
                                        <flux:icon.arrow-path class="w-5 h-5 animate-spin" />
                                        Appending...
                                    @else
                                        <flux:icon.plus class="w-5 h-5" />
                                        Append
                                    @endif
                                </flux:button>
                            </div>
                        </div>

                        {{-- Clear Prompt --}}
                        <div>
                            <flux:button
                                wire:click="clearPrompt"
                                :disabled="$isClearingPrompt"
                                variant="danger">
                                @if ($isClearingPrompt)
                                    <flux:icon.arrow-path class="w-5 h-5 animate-spin" />
                                    Clearing...
                                @else
                                    <flux:icon.trash class="w-5 h-5" />
                                    Clear Prompt
                                @endif
                            </flux:button>
                        </div>
                    </div>

                    {{-- Success Message --}}
                    @if ($success)
                        <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <p class="text-base text-green-700 dark:text-green-300">
                                {{ $success }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Quick Actions Section --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <flux:heading size="xl" class="mb-4">
                        Quick Actions
                    </flux:heading>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Common TUI operations with one click
                    </p>
                    {{-- Quick action buttons will be added in a later task --}}
                </div>

                {{-- Command Execution Section --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <flux:heading size="xl" class="mb-4">
                        Command Execution
                    </flux:heading>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Execute commands in the TUI
                    </p>
                    {{-- Command execution controls will be added in a later task --}}
                </div>
            </div>
        </div>
    @endif
</div>
