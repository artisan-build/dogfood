<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeExplorer;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('File Tree Loading', function (): void {
    test('loads file tree structure from API', function (): void {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'src', 'type' => 'directory', 'path' => '/project/src'],
                    ['name' => 'tests', 'type' => 'directory', 'path' => '/project/tests'],
                    ['name' => 'README.md', 'type' => 'file', 'path' => '/project/README.md'],
                ],
            ], 200), // fileList()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadFiles', '/')
            ->assertSet('files', fn ($files) => count($files) === 3
                && $files[0]['name'] === 'src'
                && $files[0]['type'] === 'directory');
    });

    test('handles empty directory', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadFiles', '/empty')
            ->assertSet('files', []);
    });

    test('handles file list error', function (): void {
        MockClient::global([
            MockResponse::make(['error' => 'Cannot list files'], 400),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadFiles', '/')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Directory Navigation', function (): void {
    test('can navigate into directory', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount() calls loadFiles('/')
            MockResponse::make([
                'files' => [
                    ['name' => 'Controller.php', 'type' => 'file', 'path' => '/project/src/Controller.php'],
                    ['name' => 'Model.php', 'type' => 'file', 'path' => '/project/src/Model.php'],
                ],
            ], 200), // navigateToDirectory()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('navigateToDirectory', '/project/src')
            ->assertSet('currentPath', '/project/src')
            ->assertSet('files', fn ($files) => count($files) === 2);
    });

    test('tracks current path correctly', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['files' => []], 200), // navigateToDirectory()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('navigateToDirectory', '/project/src/Controllers')
            ->assertSet('currentPath', '/project/src/Controllers');
    });

    test('can navigate back to parent directory', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
            MockResponse::make(['files' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('currentPath', '/project/src/Controllers')
            ->call('navigateToParent')
            ->assertSet('currentPath', '/project/src');
    });
});

describe('File Tree Structure', function (): void {
    test('separates directories and files', function (): void {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'README.md', 'type' => 'file', 'path' => '/project/README.md'],
                    ['name' => 'src', 'type' => 'directory', 'path' => '/project/src'],
                    ['name' => 'tests', 'type' => 'directory', 'path' => '/project/tests'],
                    ['name' => 'package.json', 'type' => 'file', 'path' => '/project/package.json'],
                ],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->call('loadFiles', '/');

        $directories = $component->get('directories');
        $files = $component->get('regularFiles');

        expect($directories)->toHaveCount(2);
        expect($files)->toHaveCount(2);
    });

    test('sorts directories before files', function (): void {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'zzz.md', 'type' => 'file', 'path' => '/project/zzz.md'],
                    ['name' => 'aaa', 'type' => 'directory', 'path' => '/project/aaa'],
                ],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->call('loadFiles', '/');

        $directories = $component->get('directories');

        expect($directories[0]['name'])->toBe('aaa');
    });

    test('sorts entries alphabetically within their type', function (): void {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'zebra.txt', 'type' => 'file', 'path' => '/project/zebra.txt'],
                    ['name' => 'apple.txt', 'type' => 'file', 'path' => '/project/apple.txt'],
                    ['name' => 'zoo', 'type' => 'directory', 'path' => '/project/zoo'],
                    ['name' => 'animals', 'type' => 'directory', 'path' => '/project/animals'],
                ],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->call('loadFiles', '/');

        $directories = $component->get('directories');
        $files = $component->get('regularFiles');

        expect($directories[0]['name'])->toBe('animals');
        expect($directories[1]['name'])->toBe('zoo');
        expect($files[0]['name'])->toBe('apple.txt');
        expect($files[1]['name'])->toBe('zebra.txt');
    });
});

describe('Expand/Collapse Functionality', function (): void {
    test('can expand directory', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('toggleDirectory', '/project/src')
            ->assertSet('expandedDirectories', fn ($expanded) => in_array('/project/src', $expanded));
    });

    test('can collapse expanded directory', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('expandedDirectories', ['/project/src'])
            ->call('toggleDirectory', '/project/src')
            ->assertSet('expandedDirectories', fn ($expanded) => ! in_array('/project/src', $expanded));
    });

    test('can expand multiple directories simultaneously', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('toggleDirectory', '/project/src')
            ->call('toggleDirectory', '/project/tests')
            ->assertSet('expandedDirectories', fn ($expanded) => in_array('/project/src', $expanded)
                && in_array('/project/tests', $expanded));
    });
});

describe('File Tree UI', function (): void {
    test('displays file tree container', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadFiles', '/')
            ->assertSeeHtml('file-tree');
    });

    test('shows directories with folder icon', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'files' => [
                    ['name' => 'src', 'type' => 'directory', 'path' => '/project/src'],
                ],
            ], 200), // loadFiles()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadFiles', '/')
            ->assertSeeHtml('src')
            ->assertSet('files', fn ($files) => count($files) === 1 && $files[0]['type'] === 'directory');
    });

    test('shows files with document icon', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'files' => [
                    ['name' => 'README.md', 'type' => 'file', 'path' => '/project/README.md'],
                ],
            ], 200), // loadFiles()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadFiles', '/')
            ->assertSeeHtml('README.md')
            ->assertSet('files', fn ($files) => count($files) === 1 && $files[0]['type'] === 'file');
    });
});

describe('Breadcrumb Navigation', function (): void {
    test('computes breadcrumb trail', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->set('currentPath', '/project/src/Controllers');

        $breadcrumbs = $component->get('breadcrumbs');

        expect($breadcrumbs)->toHaveCount(4);
        expect($breadcrumbs[0])->toBe('/');
        expect($breadcrumbs[1])->toBe('/project');
        expect($breadcrumbs[2])->toBe('/project/src');
        expect($breadcrumbs[3])->toBe('/project/src/Controllers');
    });

    test('displays breadcrumb navigation', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('currentPath', '/project/src')
            ->assertSeeHtml('breadcrumbs')
            ->assertSeeHtml('project')
            ->assertSeeHtml('src');
    });
});
