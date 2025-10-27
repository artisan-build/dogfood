<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeExplorer;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('Text Search', function (): void {
    test('can search for text across files', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'results' => [
                    [
                        'path' => '/project/src/Controller.php',
                        'line' => 42,
                        'content' => 'public function handleRequest()',
                        'preview' => '    public function handleRequest() {',
                    ],
                    [
                        'path' => '/project/tests/ControllerTest.php',
                        'line' => 15,
                        'content' => 'test handleRequest returns response',
                        'preview' => "test('handleRequest returns response', function () {",
                    ],
                ],
            ], 200), // searchText()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'handleRequest')
            ->assertSet('searchResults', fn ($results) => count($results) === 2
                && $results[0]['path'] === '/project/src/Controller.php'
                && $results[0]['line'] === 42)
            ->assertSet('searchQuery', 'handleRequest');
    });

    test('handles empty text search results', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['results' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'nonexistent')
            ->assertSet('searchResults', []);
    });

    test('handles text search error', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['error' => 'Search failed'], 400),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'query')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('File Search', function (): void {
    test('can search for files by name', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'results' => [
                    ['path' => '/project/src/Controller.php', 'type' => 'file'],
                    ['path' => '/project/app/Http/Controllers/UserController.php', 'type' => 'file'],
                ],
            ], 200), // searchFiles()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchFiles', 'Controller')
            ->assertSet('searchResults', fn ($results) => count($results) === 2
                && $results[0]['path'] === '/project/src/Controller.php');
    });

    test('handles empty file search results', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['results' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchFiles', 'nonexistent.php')
            ->assertSet('searchResults', []);
    });

    test('handles file search error', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['error' => 'Search failed'], 400),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchFiles', 'query')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Symbol Search', function (): void {
    test('can search for code symbols', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'results' => [
                    [
                        'path' => '/project/src/User.php',
                        'line' => 10,
                        'type' => 'class',
                        'name' => 'User',
                    ],
                    [
                        'path' => '/project/src/UserService.php',
                        'line' => 20,
                        'type' => 'class',
                        'name' => 'UserService',
                    ],
                ],
            ], 200), // searchSymbols()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchSymbols', 'User')
            ->assertSet('searchResults', fn ($results) => count($results) === 2
                && $results[0]['name'] === 'User'
                && $results[0]['type'] === 'class');
    });

    test('handles empty symbol search results', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['results' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchSymbols', 'NonExistentClass')
            ->assertSet('searchResults', []);
    });

    test('handles symbol search error', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['error' => 'Search failed'], 400),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchSymbols', 'query')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Search Mode Management', function (): void {
    test('tracks current search mode', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('searchMode', 'text')
            ->assertSet('searchMode', 'text')
            ->set('searchMode', 'files')
            ->assertSet('searchMode', 'files')
            ->set('searchMode', 'symbols')
            ->assertSet('searchMode', 'symbols');
    });

    test('clears results when switching modes', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['results' => [['path' => '/test.php']]], 200), // searchText()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'query')
            ->assertSet('searchResults', fn ($results) => count($results) === 1)
            ->call('clearSearch')
            ->assertSet('searchResults', [])
            ->assertSet('searchQuery', '');
    });
});

describe('Search UI Display', function (): void {
    test('shows search panel when searching', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['results' => [['path' => '/test.php']]], 200), // searchText()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'query')
            ->assertSeeHtml('search-panel');
    });

    test('shows search mode tabs', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('showSearch', true)
            ->assertSeeHtml('Text')
            ->assertSeeHtml('Files')
            ->assertSeeHtml('Symbols');
    });

    test('displays search results with file paths', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'results' => [
                    [
                        'path' => '/project/src/Controller.php',
                        'line' => 42,
                        'content' => 'public function test()',
                        'preview' => '    public function test() {',
                    ],
                ],
            ], 200), // searchText()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'test')
            ->assertSeeHtml('Controller.php')
            ->assertSeeHtml('public function test()');
    });

    test('shows line numbers in text search results', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'results' => [
                    [
                        'path' => '/project/test.php',
                        'line' => 42,
                        'content' => 'test content',
                        'preview' => 'test content',
                    ],
                ],
            ], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'test')
            ->assertSeeHtml('42');
    });

    test('shows empty state when no results', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['results' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'nonexistent')
            ->assertSeeHtml('No results found');
    });
});

describe('Search Result Actions', function (): void {
    test('can open file from text search result', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'results' => [
                    [
                        'path' => '/project/test.php',
                        'line' => 10,
                        'content' => 'test',
                        'preview' => 'test',
                    ],
                ],
            ], 200), // searchText()
            MockResponse::make([
                'path' => '/project/test.php',
                'content' => "<?php\n\ntest content",
                'language' => 'php',
            ], 200), // viewFile()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'test')
            ->call('viewFile', '/project/test.php')
            ->assertSet('currentFile', '/project/test.php');
    });

    test('can open file from file search result', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'results' => [
                    ['path' => '/project/test.php', 'type' => 'file'],
                ],
            ], 200), // searchFiles()
            MockResponse::make([
                'path' => '/project/test.php',
                'content' => 'test content',
                'language' => 'php',
            ], 200), // viewFile()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchFiles', 'test')
            ->call('viewFile', '/project/test.php')
            ->assertSet('currentFile', '/project/test.php');
    });

    test('can open file from symbol search result', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'results' => [
                    [
                        'path' => '/project/User.php',
                        'line' => 10,
                        'type' => 'class',
                        'name' => 'User',
                    ],
                ],
            ], 200), // searchSymbols()
            MockResponse::make([
                'path' => '/project/User.php',
                'content' => 'class User {}',
                'language' => 'php',
            ], 200), // viewFile()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchSymbols', 'User')
            ->call('viewFile', '/project/User.php')
            ->assertSet('currentFile', '/project/User.php');
    });
});

describe('Search Query Persistence', function (): void {
    test('retains search query after search', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['results' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'my query')
            ->assertSet('searchQuery', 'my query');
    });

    test('clears search query when clearing search', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['results' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('searchText', 'query')
            ->assertSet('searchQuery', 'query')
            ->call('clearSearch')
            ->assertSet('searchQuery', '');
    });
});
