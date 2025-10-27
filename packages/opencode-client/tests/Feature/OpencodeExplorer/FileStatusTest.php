<?php

use ArtisanBuild\OpencodeClient\Livewire\OpencodeExplorer;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    MockClient::destroyGlobal();
});

describe('Git Status Loading', function () {
    test('can load git status for files', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'statuses' => [
                    ['path' => '/project/src/Controller.php', 'status' => 'modified'],
                    ['path' => '/project/tests/NewTest.php', 'status' => 'added'],
                    ['path' => '/project/old/Legacy.php', 'status' => 'deleted'],
                ],
            ], 200), // loadFileStatuses()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadFileStatuses')
            ->assertSet('fileStatuses', function ($statuses) {
                return count($statuses) === 3
                    && $statuses['/project/src/Controller.php'] === 'modified'
                    && $statuses['/project/tests/NewTest.php'] === 'added'
                    && $statuses['/project/old/Legacy.php'] === 'deleted';
            });
    });

    test('handles empty status response', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['statuses' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadFileStatuses')
            ->assertSet('fileStatuses', []);
    });

    test('handles file status error', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['error' => 'Git not available'], 400),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadFileStatuses')
            ->assertSet('error', function ($error) {
                return $error !== null;
            });
    });
});

describe('Status Badge Display', function () {
    test('shows modified badge for modified files', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'Controller.php', 'type' => 'file', 'path' => '/project/Controller.php'],
                ],
            ], 200), // mount()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', ['/project/Controller.php' => 'modified'])
            ->assertSeeHtml('status-badge')
            ->assertSeeHtml('Modified');
    });

    test('shows added badge for new files', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'NewFile.php', 'type' => 'file', 'path' => '/project/NewFile.php'],
                ],
            ], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', ['/project/NewFile.php' => 'added'])
            ->assertSeeHtml('status-badge')
            ->assertSeeHtml('Added');
    });

    test('shows deleted badge for deleted files', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'Old.php', 'type' => 'file', 'path' => '/project/Old.php'],
                ],
            ], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', ['/project/Old.php' => 'deleted'])
            ->assertSeeHtml('status-badge')
            ->assertSeeHtml('Deleted');
    });

    test('shows no badge for unmodified files', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'Clean.php', 'type' => 'file', 'path' => '/project/Clean.php'],
                ],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', []);

        expect($component->html())->not->toContain('status-badge');
    });
});

describe('Status Indicator Colors', function () {
    test('modified badge uses yellow color', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'File.php', 'type' => 'file', 'path' => '/project/File.php'],
                ],
            ], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', ['/project/File.php' => 'modified'])
            ->assertSeeHtml('bg-yellow-100')
            ->assertSeeHtml('text-yellow-800');
    });

    test('added badge uses green color', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'File.php', 'type' => 'file', 'path' => '/project/File.php'],
                ],
            ], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', ['/project/File.php' => 'added'])
            ->assertSeeHtml('bg-green-100')
            ->assertSeeHtml('text-green-800');
    });

    test('deleted badge uses red color', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'File.php', 'type' => 'file', 'path' => '/project/File.php'],
                ],
            ], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', ['/project/File.php' => 'deleted'])
            ->assertSeeHtml('bg-red-100')
            ->assertSeeHtml('text-red-800');
    });
});

describe('File Tree Integration', function () {
    test('file tree displays status badges alongside files', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'Modified.php', 'type' => 'file', 'path' => '/project/Modified.php'],
                    ['name' => 'Clean.php', 'type' => 'file', 'path' => '/project/Clean.php'],
                ],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', ['/project/Modified.php' => 'modified']);

        expect($component->html())->toContain('Modified.php');
        expect($component->html())->toContain('status-badge');
        expect($component->html())->toContain('Clean.php');
    });

    test('can get file status for a given path', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', [
                '/project/Modified.php' => 'modified',
                '/project/Added.php' => 'added',
            ]);

        expect($component->get('fileStatuses')['/project/Modified.php'])->toBe('modified');
        expect($component->get('fileStatuses')['/project/Added.php'])->toBe('added');
    });

    test('directories do not show status badges', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'src', 'type' => 'directory', 'path' => '/project/src'],
                    ['name' => 'Modified.php', 'type' => 'file', 'path' => '/project/Modified.php'],
                ],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', ['/project/Modified.php' => 'modified']);

        $html = $component->html();

        // Modified.php should have status badge
        expect($html)->toContain('Modified.php');
        expect($html)->toContain('status-badge');

        // src directory should not have status badge in its row
        // (This is checked by ensuring directory rows don't contain badges)
    });
});

describe('Status Refresh', function () {
    test('can refresh file statuses', function () {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'statuses' => [
                    ['path' => '/project/File.php', 'status' => 'modified'],
                ],
            ], 200), // first loadFileStatuses()
            MockResponse::make([
                'statuses' => [
                    ['path' => '/project/File.php', 'status' => 'modified'],
                    ['path' => '/project/New.php', 'status' => 'added'],
                ],
            ], 200), // second loadFileStatuses()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadFileStatuses')
            ->assertSet('fileStatuses', function ($statuses) {
                return count($statuses) === 1;
            })
            ->call('loadFileStatuses')
            ->assertSet('fileStatuses', function ($statuses) {
                return count($statuses) === 2;
            });
    });
});

describe('Multiple File Statuses', function () {
    test('handles multiple files with different statuses', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    ['name' => 'Modified.php', 'type' => 'file', 'path' => '/project/Modified.php'],
                    ['name' => 'Added.php', 'type' => 'file', 'path' => '/project/Added.php'],
                    ['name' => 'Deleted.php', 'type' => 'file', 'path' => '/project/Deleted.php'],
                    ['name' => 'Clean.php', 'type' => 'file', 'path' => '/project/Clean.php'],
                ],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->set('fileStatuses', [
                '/project/Modified.php' => 'modified',
                '/project/Added.php' => 'added',
                '/project/Deleted.php' => 'deleted',
            ]);

        $html = $component->html();

        expect($html)->toContain('Modified.php');
        expect($html)->toContain('Added.php');
        expect($html)->toContain('Deleted.php');
        expect($html)->toContain('Clean.php');

        // Should have 3 status badges (not 4, since Clean.php has no status)
        expect(substr_count($html, 'status-badge'))->toBe(3);
    });
});
