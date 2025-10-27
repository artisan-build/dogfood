<div class="flex flex-col h-screen bg-gray-50 dark:bg-gray-900">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <flux:heading size="xl">
                        Project Explorer
                    </flux:heading>
                    @if ($currentProject)
                        <div class="relative">
                            <button
                                wire:click="toggleProjectDropdown"
                                class="flex items-center gap-2 px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $currentProject['name'] }}
                                </span>
                                <flux:icon.chevron-down class="w-4 h-4 text-gray-500" />
                            </button>

                            @if ($showProjectDropdown)
                                <div class="project-dropdown absolute top-full left-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                                    <div class="p-2 max-h-64 overflow-auto">
                                        @foreach ($availableProjects as $project)
                                            <button
                                                wire:click="switchProject('{{ $project['path'] }}')"
                                                class="w-full text-left px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ $currentProject['path'] === $project['path'] ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $project['name'] }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                    {{ $project['path'] }}
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Browse project files and directories
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                <flux:button
                    wire:click="$toggle('showSearch')"
                    variant="ghost">
                    <flux:icon.magnifying-glass class="w-5 h-5" />
                    Search
                </flux:button>
                <flux:button
                    wire:click="loadFileStatuses"
                    variant="ghost">
                    <flux:icon.arrow-path class="w-5 h-5" />
                    Refresh
                </flux:button>
            </div>
        </div>

        {{-- Breadcrumb Navigation --}}
        @if (count($this->breadcrumbs) > 0)
            <div class="breadcrumbs mt-4 flex items-center gap-2 text-sm">
                @foreach ($this->breadcrumbs as $index => $breadcrumb)
                    @if ($index > 0)
                        <flux:icon.chevron-right class="w-4 h-4 text-gray-400" />
                    @endif
                    <button
                        wire:click="navigateToDirectory('{{ $breadcrumb }}')"
                        class="text-blue-600 dark:text-blue-400 hover:underline">
                        {{ $breadcrumb === '/' ? 'root' : basename($breadcrumb) }}
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Error Messages --}}
    @if ($error)
        <div class="mx-6 mt-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center gap-2">
                <flux:icon.exclamation-circle class="w-5 h-5 text-red-600 dark:text-red-400" />
                <p class="text-red-700 dark:text-red-300">{{ $error }}</p>
            </div>
        </div>
    @endif

    {{-- Search Panel --}}
    @if ($showSearch)
        <div class="search-panel mx-6 mt-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            {{-- Search Mode Tabs --}}
            <div class="border-b border-gray-200 dark:border-gray-700 p-3 flex gap-2">
                <button
                    wire:click="$set('searchMode', 'text')"
                    class="px-3 py-1.5 text-sm font-medium rounded {{ $searchMode === 'text' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Text
                </button>
                <button
                    wire:click="$set('searchMode', 'files')"
                    class="px-3 py-1.5 text-sm font-medium rounded {{ $searchMode === 'files' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Files
                </button>
                <button
                    wire:click="$set('searchMode', 'symbols')"
                    class="px-3 py-1.5 text-sm font-medium rounded {{ $searchMode === 'symbols' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Symbols
                </button>
                <button
                    wire:click="clearSearch"
                    class="ml-auto px-3 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                    <flux:icon.x-mark class="w-4 h-4" />
                </button>
            </div>

            {{-- Search Results --}}
            <div class="max-h-96 overflow-auto">
                @if (count($searchResults) === 0)
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <flux:icon.magnifying-glass class="w-12 h-12 mx-auto mb-3 opacity-50" />
                        <p>No results found</p>
                    </div>
                @else
                    @foreach ($searchResults as $result)
                        <button
                            wire:click="viewFile('{{ $result['path'] }}')"
                            class="w-full text-left p-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="flex items-start gap-3">
                                <flux:icon.document class="w-5 h-5 text-gray-400 mt-0.5" />
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                            {{ basename($result['path']) }}
                                        </span>
                                        @if (isset($result['line']))
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                :{{ $result['line'] }}
                                            </span>
                                        @endif
                                        @if (isset($result['type']))
                                            <span class="px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                {{ $result['type'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                        {{ $result['path'] }}
                                    </p>
                                    @if (isset($result['preview']) || isset($result['content']))
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 font-mono truncate">
                                            {{ $result['preview'] ?? $result['content'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @endforeach
                @endif
            </div>
        </div>
    @endif

    {{-- File Tree --}}
    <div class="flex-1 overflow-auto p-6">
        <div class="file-tree bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            {{-- Parent Directory Navigation --}}
            @if ($currentPath !== '/')
                <div class="border-b border-gray-200 dark:border-gray-700 p-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <button
                        wire:click="navigateToParent"
                        class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 w-full text-left">
                        <flux:icon.arrow-up class="w-4 h-4" />
                        <span class="font-medium">.. (Parent Directory)</span>
                    </button>
                </div>
            @endif

            {{-- Directories --}}
            @foreach ($this->directories as $directory)
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2 p-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <button
                            wire:click="toggleDirectory('{{ $directory['path'] }}')"
                            class="text-gray-500 dark:text-gray-400">
                            @if (in_array($directory['path'], $expandedDirectories))
                                <flux:icon.chevron-down class="w-4 h-4" />
                            @else
                                <flux:icon.chevron-right class="w-4 h-4" />
                            @endif
                        </button>
                        <button
                            wire:click="navigateToDirectory('{{ $directory['path'] }}')"
                            class="flex items-center gap-2 flex-1 text-left">
                            <flux:icon.folder class="w-5 h-5 text-blue-500" />
                            <span class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $directory['name'] }}
                            </span>
                        </button>
                    </div>
                </div>
            @endforeach

            {{-- Regular Files --}}
            @foreach ($this->regularFiles as $file)
                <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <button
                        wire:click="viewFile('{{ $file['path'] }}')"
                        class="flex items-center gap-2 p-3 pl-9 hover:bg-gray-50 dark:hover:bg-gray-700 w-full text-left">
                        <flux:icon.document class="w-5 h-5 text-gray-400" />
                        <span class="text-gray-700 dark:text-gray-300 flex-1">
                            {{ $file['name'] }}
                        </span>

                        {{-- Status Badge --}}
                        @if (isset($fileStatuses[$file['path']]))
                            @php
                                $status = $fileStatuses[$file['path']];
                                $statusColors = [
                                    'modified' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'added' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'deleted' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                ];
                                $statusText = [
                                    'modified' => 'Modified',
                                    'added' => 'Added',
                                    'deleted' => 'Deleted',
                                ];
                                $colorClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                $displayText = $statusText[$status] ?? ucfirst($status);
                            @endphp
                            <span class="status-badge px-2 py-1 text-xs font-medium rounded {{ $colorClass }}">
                                {{ $displayText }}
                            </span>
                        @endif
                    </button>
                </div>
            @endforeach

            {{-- Empty State --}}
            @if (empty($this->directories) && empty($this->regularFiles))
                <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                    <flux:icon.folder-open class="w-16 h-16 mx-auto mb-4 opacity-50" />
                    <p class="text-lg font-medium mb-1">Empty Directory</p>
                    <p class="text-sm">No files or folders found in this directory</p>
                </div>
            @endif
        </div>
    </div>

    {{-- File Viewer Modal --}}
    @if ($currentFile)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             wire:click="closeFileViewer">
            <div class="file-viewer bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-6xl mx-4 max-h-[90vh] flex flex-col"
                 wire:click.stop>
                {{-- Header --}}
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <flux:icon.document class="w-6 h-6 text-blue-500" />
                        <div>
                            <flux:heading size="lg">{{ basename($currentFile) }}</flux:heading>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $currentFile }}
                            </p>
                        </div>
                    </div>
                    <flux:button
                        wire:click="closeFileViewer"
                        variant="ghost"
                        size="sm">
                        <flux:icon.x-mark class="w-5 h-5" />
                    </flux:button>
                </div>

                {{-- File Content with Line Numbers --}}
                <div class="flex-1 overflow-auto bg-gray-50 dark:bg-gray-900">
                    <div class="line-numbers flex font-mono text-sm">
                        {{-- Line Numbers Column --}}
                        <div class="bg-gray-100 dark:bg-gray-800 px-4 py-3 text-right text-gray-500 dark:text-gray-400 select-none border-r border-gray-200 dark:border-gray-700">
                            @for ($i = 1; $i <= $this->lineCount; $i++)
                                <div>{{ $i }}</div>
                            @endfor
                        </div>

                        {{-- Content Column --}}
                        <div class="flex-1 px-4 py-3">
                            <pre class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap break-all"><code class="language-{{ $fileLanguage }}">{{ $fileContent }}</code></pre>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                        <span>Language: {{ $fileLanguage }}</span>
                        <span>{{ $this->lineCount }} {{ Str::plural('line', $this->lineCount) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
