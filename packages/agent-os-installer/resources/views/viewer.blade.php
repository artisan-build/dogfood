<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent OS Documentation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">
                Agent OS Documentation Viewer
            </h1>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    🎉 The Agent OS Web Viewer is working!
                </p>

                <div class="space-y-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            What's been completed:
                        </h2>
                        <ul class="list-disc list-inside space-y-1 text-gray-700 dark:text-gray-300">
                            <li>Configuration system with environment variables</li>
                            <li>Directory scanner for .agent-os folder</li>
                            <li>Product folder concatenation service</li>
                            <li>Spec concatenation service</li>
                            <li>Markdown rendering with CommonMark</li>
                            <li>Search functionality</li>
                            <li>Route registration (you're seeing this!)</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Still to build:
                        </h2>
                        <ul class="list-disc list-inside space-y-1 text-gray-700 dark:text-gray-300">
                            <li>Livewire components for navigation and content display</li>
                            <li>Full UI with sidebar navigation</li>
                            <li>Search interface</li>
                            <li>Access control middleware</li>
                        </ul>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <strong>Testing the services:</strong> All 29 tests are passing for the core services (DirectoryScanner, ProductConcatenationService, SpecConcatenationService, AgentOsSearchService, MarkdownRenderer).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
