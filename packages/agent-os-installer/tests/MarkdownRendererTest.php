<?php

declare(strict_types=1);

use ArtisanBuild\AgentOsInstaller\Services\MarkdownRenderer;

test('it renders basic markdown to HTML', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = '# Heading 1';
    $html = $renderer->render($markdown);

    expect($html)->toContain('<h1>Heading 1</h1>');
});

test('it renders GitHub Flavored Markdown', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = <<<'MD'
        # Heading

        Some **bold** and *italic* text.

        ~~Strikethrough~~
        MD;

    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('<h1>Heading</h1>')
        ->toContain('<strong>bold</strong>')
        ->toContain('<em>italic</em>')
        ->toContain('<del>Strikethrough</del>');
});

test('it renders tables', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = <<<'MD'
        | Header 1 | Header 2 |
        |----------|----------|
        | Cell 1   | Cell 2   |
        MD;

    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('<table>')
        ->toContain('<thead>')
        ->toContain('<th>Header 1</th>')
        ->toContain('<td>Cell 1</td>');
});

test('it renders task lists', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = <<<'MD'
        - [x] Completed task
        - [ ] Incomplete task
        MD;

    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('type="checkbox"')
        ->toContain('checked')
        ->toContain('Completed task')
        ->toContain('Incomplete task');
});

test('it renders code blocks', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = <<<'MD'
        ```php
        echo "Hello World";
        ```
        MD;

    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('<code')
        ->toContain('echo &quot;Hello World&quot;');
});

test('it allows HTML since content is trusted', function (): void {
    $renderer = new MarkdownRenderer;

    // HTML is allowed because all content is developer-created and trusted
    $markdown = '<p align="center"><img src="logo.webp" width="75%" alt="Logo"></p>';
    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('<p align="center">')
        ->toContain('<img src="logo.webp"');
});

test('it does not allow unsafe links', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = '[Click](javascript:alert("XSS"))';
    $html = $renderer->render($markdown);

    expect($html)->not->toContain('javascript:');
});

test('it converts Agent OS @ reference links to internal navigation', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = 'See @.agent-os/product/mission.md for details.';
    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('<a href="/.agent-os/product/mission.md">')
        ->toContain('product/mission.md</a>');
});

test('it converts multiple @ reference links', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = <<<'MD'
        Check @.agent-os/product/mission.md and @.agent-os/product/roadmap.md for more info.
        MD;

    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('<a href="/.agent-os/product/mission.md">product/mission.md</a>')
        ->toContain('<a href="/.agent-os/product/roadmap.md">product/roadmap.md</a>');
});

test('it handles @ reference links in lists', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = <<<'MD'
        - See @.agent-os/product/mission.md
        - Check @.agent-os/specs/2025-11-01-test-spec/spec.md
        MD;

    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('<a href="/.agent-os/product/mission.md">')
        ->toContain('<a href="/.agent-os/specs/2025-11-01-test-spec/spec.md">');
});

test('it only converts @ links with .md extension', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = 'Email me @user.name and check @.agent-os/product/mission.md';
    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('@user.name') // Email should not be converted
        ->toContain('<a href="/.agent-os/product/mission.md">'); // @ link should be converted
});

test('it converts sub-spec references to anchor links', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = 'See @.agent-os/specs/2025-11-01-test-spec/sub-specs/technical-spec.md for details.';
    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('<a href="#technical-spec">')
        ->toContain('specs/2025-11-01-test-spec/sub-specs/technical-spec.md</a>')
        ->not->toContain('/.agent-os/specs');
});

test('it converts tasks.md references to anchor links', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = 'Check @.agent-os/specs/2025-11-01-test-spec/tasks.md for the task list.';
    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('<a href="#tasks">')
        ->toContain('specs/2025-11-01-test-spec/tasks.md</a>');
});

test('it handles multiple sub-spec anchor links', function (): void {
    $renderer = new MarkdownRenderer;

    $markdown = <<<'MD'
        Review @.agent-os/specs/2025-11-01-spec/sub-specs/technical-spec.md
        and @.agent-os/specs/2025-11-01-spec/sub-specs/database-schema.md.
        MD;

    $html = $renderer->render($markdown);

    expect($html)
        ->toContain('<a href="#technical-spec">')
        ->toContain('<a href="#database-schema">');
});
