<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent OS Documentation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* GitHub-flavored markdown styles with system dark mode support */
        @media (prefers-color-scheme: light) {
            :root {
                --color-canvas-default: #ffffff;
                --color-canvas-subtle: #f6f8fa;
                --color-border-default: #d0d7de;
                --color-border-muted: #d8dee4;
                --color-fg-default: #1f2328;
                --color-fg-muted: #656d76;
                --color-accent-fg: #0969da;
                --color-accent-emphasis: #0969da;
                --color-danger-fg: #d1242f;
                --color-attention-fg: #9a6700;
                --color-done-fg: #1a7f37;
                --color-neutral-muted: rgba(175, 184, 193, 0.2);
                --color-code-bg: rgba(175, 184, 193, 0.2);
            }
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --color-canvas-default: #0d1117;
                --color-canvas-subtle: #161b22;
                --color-border-default: #30363d;
                --color-border-muted: #21262d;
                --color-fg-default: #e6edf3;
                --color-fg-muted: #8d96a0;
                --color-accent-fg: #4493f8;
                --color-accent-emphasis: #4493f8;
                --color-danger-fg: #f85149;
                --color-attention-fg: #d29922;
                --color-done-fg: #3fb950;
                --color-neutral-muted: rgba(110, 118, 129, 0.4);
                --color-code-bg: rgba(110, 118, 129, 0.4);
            }
        }
    </style>
</head>
<body style="background: var(--color-canvas-default); color: var(--color-fg-default); margin: 0; padding: 0;">
    @livewire('agent-os-viewer', ['basePath' => base_path(), 'path' => $path ?? ''])
</body>
</html>
