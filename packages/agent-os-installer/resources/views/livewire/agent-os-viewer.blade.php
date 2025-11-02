<div class="min-h-screen" style="background: var(--color-canvas-default);">
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="w-64 min-h-screen p-4 overflow-y-auto" style="border-right: 1px solid var(--color-border-default); background: var(--color-canvas-subtle);">
            <div class="mb-6">
                <h1 class="text-xl font-bold" style="color: var(--color-fg-default);">{{ config('app.name') }}</h1>
                <p class="text-sm" style="color: var(--color-fg-muted);">Documentation</p>
            </div>

            <livewire:sidebar-navigation
                :base-path="$basePath"
                :current-path="$path"
            />
        </aside>

        {{-- Main content area --}}
        <main class="flex-1 overflow-y-auto">
            <div class="max-w-4xl mx-auto px-8 py-12">
                <article class="markdown-body" style="
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif;
                    font-size: 16px;
                    line-height: 1.6;
                    color: var(--color-fg-default);
                ">
                    <style>
                        .markdown-body h1,
                        .markdown-body h2,
                        .markdown-body h3,
                        .markdown-body h4,
                        .markdown-body h5,
                        .markdown-body h6 {
                            margin-top: 24px;
                            margin-bottom: 16px;
                            font-weight: 600;
                            line-height: 1.25;
                            color: var(--color-fg-default);
                        }

                        .markdown-body h1 {
                            font-size: 2em;
                            padding-bottom: 0.3em;
                            border-bottom: 1px solid var(--color-border-muted);
                        }

                        .markdown-body h2 {
                            font-size: 1.5em;
                            padding-bottom: 0.3em;
                            border-bottom: 1px solid var(--color-border-muted);
                        }

                        .markdown-body h3 { font-size: 1.25em; }
                        .markdown-body h4 { font-size: 1em; }
                        .markdown-body h5 { font-size: 0.875em; }
                        .markdown-body h6 { font-size: 0.85em; color: var(--color-fg-muted); }

                        .markdown-body p {
                            margin-top: 0;
                            margin-bottom: 16px;
                        }

                        .markdown-body a {
                            color: var(--color-accent-fg);
                            text-decoration: none;
                        }

                        .markdown-body a:hover {
                            text-decoration: underline;
                        }

                        .markdown-body ul,
                        .markdown-body ol {
                            margin-top: 0;
                            margin-bottom: 16px;
                            padding-left: 2em;
                        }

                        .markdown-body li {
                            margin-top: 0.25em;
                        }

                        .markdown-body li + li {
                            margin-top: 0.25em;
                        }

                        .markdown-body code {
                            padding: 0.2em 0.4em;
                            margin: 0;
                            font-size: 85%;
                            background-color: var(--color-neutral-muted);
                            border-radius: 6px;
                            font-family: ui-monospace, SFMono-Regular, 'SF Mono', Menlo, Consolas, 'Liberation Mono', monospace;
                        }

                        .markdown-body pre {
                            padding: 16px;
                            overflow: auto;
                            font-size: 85%;
                            line-height: 1.45;
                            background-color: var(--color-canvas-subtle);
                            border-radius: 6px;
                            margin-bottom: 16px;
                            border: 1px solid var(--color-border-default);
                        }

                        .markdown-body pre code {
                            padding: 0;
                            margin: 0;
                            background-color: transparent;
                            border: 0;
                            font-size: 100%;
                        }

                        .markdown-body blockquote {
                            padding: 0 1em;
                            color: var(--color-fg-muted);
                            border-left: 0.25em solid var(--color-border-default);
                            margin: 0 0 16px 0;
                        }

                        .markdown-body table {
                            border-spacing: 0;
                            border-collapse: collapse;
                            display: block;
                            width: max-content;
                            max-width: 100%;
                            overflow: auto;
                            margin-bottom: 16px;
                        }

                        .markdown-body table tr {
                            background-color: var(--color-canvas-default);
                            border-top: 1px solid var(--color-border-muted);
                        }

                        .markdown-body table tr:nth-child(2n) {
                            background-color: var(--color-canvas-subtle);
                        }

                        .markdown-body table th,
                        .markdown-body table td {
                            padding: 6px 13px;
                            border: 1px solid var(--color-border-default);
                        }

                        .markdown-body table th {
                            font-weight: 600;
                            background-color: var(--color-canvas-subtle);
                        }

                        .markdown-body hr {
                            height: 0.25em;
                            padding: 0;
                            margin: 24px 0;
                            background-color: var(--color-border-default);
                            border: 0;
                        }

                        .markdown-body input[type="checkbox"] {
                            margin-right: 0.5em;
                        }

                        .markdown-body img {
                            max-width: 100%;
                            height: auto;
                        }
                    </style>
                    {!! $this->content !!}
                </article>
            </div>
        </main>
    </div>
</div>
