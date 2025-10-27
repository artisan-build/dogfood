<?php

use ArtisanBuild\OpencodeClient\Livewire\OpencodeExplorer;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    MockClient::destroyGlobal();
});

describe('File Content Loading', function () {
    test('can load file content', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'path' => '/project/README.md',
                'content' => "# My Project\n\nThis is a test project.",
                'language' => 'markdown',
            ], 200), // readFile()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('viewFile', '/project/README.md')
            ->assertSet('currentFile', '/project/README.md')
            ->assertSet('fileContent', function ($content) {
                return str_contains($content, '# My Project');
            });
    });

    test('handles file read error', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['error' => 'File not found'], 404),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('viewFile', '/project/missing.txt')
            ->assertSet('error', function ($error) {
                return $error !== null;
            });
    });

    test('can close file viewer', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('currentFile', '/project/README.md')
            ->set('fileContent', 'Some content')
            ->call('closeFileViewer')
            ->assertSet('currentFile', null)
            ->assertSet('fileContent', null);
    });
});

describe('Syntax Highlighting', function () {
    test('detects language from file extension', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'path' => '/project/index.php',
                'content' => '<?php echo "Hello"; ?>',
                'language' => 'php',
            ], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->call('viewFile', '/project/index.php');

        expect($component->get('fileLanguage'))->toBe('php');
    });

    test('supports multiple languages', function () {
        // Test JavaScript
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'path' => '/project/script.js',
                'content' => 'content',
                'language' => 'javascript',
            ], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->call('viewFile', '/project/script.js');

        expect($component->get('fileLanguage'))->toBe('javascript');

        // Test CSS in a new component instance
        MockClient::destroyGlobal();
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'path' => '/project/style.css',
                'content' => 'content',
                'language' => 'css',
            ], 200),
        ]);

        $component2 = Livewire::test(OpencodeExplorer::class)
            ->call('viewFile', '/project/style.css');

        expect($component2->get('fileLanguage'))->toBe('css');
    });
});

describe('Line Numbers', function () {
    test('displays content with line numbers', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'path' => '/project/test.txt',
                'content' => "Line 1\nLine 2\nLine 3",
                'language' => 'text',
            ], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('viewFile', '/project/test.txt')
            ->assertSeeHtml('line-numbers');
    });

    test('computes line count correctly', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->set('fileContent', "Line 1\nLine 2\nLine 3\nLine 4\nLine 5");

        expect($component->get('lineCount'))->toBe(5);
    });
});

describe('File Viewer UI', function () {
    test('shows file viewer when file is loaded', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('currentFile', '/project/README.md')
            ->set('fileContent', 'Test content')
            ->assertSeeHtml('file-viewer');
    });

    test('shows file path in viewer header', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('currentFile', '/project/src/Controller.php')
            ->set('fileContent', 'Test content')
            ->assertSeeHtml('Controller.php');
    });

    test('shows close button', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('currentFile', '/project/test.txt')
            ->set('fileContent', 'Test content')
            ->assertSeeHtml('closeFileViewer');
    });
});

describe('File Selection from Tree', function () {
    test('clicking file in tree opens viewer', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'path' => '/project/test.txt',
                'content' => 'File content',
                'language' => 'text',
            ], 200), // viewFile()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('viewFile', '/project/test.txt')
            ->assertSet('currentFile', '/project/test.txt')
            ->assertSet('fileContent', 'File content');
    });
});

describe('Empty File Handling', function () {
    test('handles empty files gracefully', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'path' => '/project/empty.txt',
                'content' => '',
                'language' => 'text',
            ], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('viewFile', '/project/empty.txt')
            ->assertSet('fileContent', '')
            ->assertSet('currentFile', '/project/empty.txt');
    });
});

describe('Large File Handling', function () {
    test('loads large files', function () {
        // Create content with exactly 1000 lines (999 newlines, plus the initial line)
        $largeContent = str_repeat("This is line content.\n", 999).'This is line content.';

        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'path' => '/project/large.txt',
                'content' => $largeContent,
                'language' => 'text',
            ], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->call('viewFile', '/project/large.txt');

        expect($component->get('lineCount'))->toBe(1000);
    });
});
