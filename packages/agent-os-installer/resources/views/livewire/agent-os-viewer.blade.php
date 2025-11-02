<div class="min-h-screen bg-white dark:bg-gray-900">
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="w-64 min-h-screen border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-4 overflow-y-auto">
            <div class="mb-6">
                <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Agent OS</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Documentation</p>
            </div>

            <livewire:sidebar-navigation
                :base-path="$basePath"
                :current-path="$path"
            />
        </aside>

        {{-- Main content area --}}
        <main class="flex-1 overflow-y-auto">
            <div class="max-w-4xl mx-auto px-8 py-12">
                <article class="prose prose-gray dark:prose-invert max-w-none
                               prose-headings:font-bold
                               prose-h1:text-3xl prose-h1:mb-4
                               prose-h2:text-2xl prose-h2:mt-8 prose-h2:mb-3
                               prose-h3:text-xl prose-h3:mt-6 prose-h3:mb-2
                               prose-p:text-gray-700 dark:prose-p:text-gray-300
                               prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-a:no-underline hover:prose-a:underline
                               prose-code:text-pink-600 dark:prose-code:text-pink-400
                               prose-pre:bg-gray-100 dark:prose-pre:bg-gray-800 prose-pre:border prose-pre:border-gray-200 dark:prose-pre:border-gray-700
                               prose-table:border prose-table:border-gray-200 dark:prose-table:border-gray-700
                               prose-th:bg-gray-100 dark:prose-th:bg-gray-800
                               prose-hr:border-gray-200 dark:prose-hr:border-gray-700">
                    {!! $this->content !!}
                </article>
            </div>
        </main>
    </div>
</div>
