<?php

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    MockClient::destroyGlobal();
});

describe('Diff Modal', function () {
    test('can open diff viewer modal', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'files' => [
                    ['path' => 'test.php', 'diff' => ''],
                ],
            ], 200), // getDiff()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('openDiffModal', 'msg_123')
            ->assertSet('showDiffModal', true)
            ->assertSet('currentMessageId', 'msg_123');
    });

    test('can close diff viewer modal', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('showDiffModal', true)
            ->call('closeDiffModal')
            ->assertSet('showDiffModal', false)
            ->assertSet('currentMessageId', null);
    });

    test('modal button appears on assistant messages', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                [
                    'role' => 'assistant',
                    'content' => 'Test message',
                    'id' => 'msg_123',
                ],
            ])
            ->assertSeeHtml('View diff');
    });
});

describe('Diff Data Loading', function () {
    test('loads diff data when modal opens', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'files' => [
                    [
                        'path' => 'app/Models/User.php',
                        'diff' => '@@ -1,3 +1,5 @@
class User
{
+    public function getName()
+    {
+    }
}',
                    ],
                ],
            ], 200), // getDiff()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('openDiffModal', 'msg_123')
            ->assertSet('diffData', function ($data) {
                return isset($data['files']) && count($data['files']) === 1;
            });
    });

    test('handles diff loading error', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Diff not available'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('openDiffModal', 'msg_123')
            ->assertSet('error', function ($error) {
                return $error !== null;
            });
    });

    test('displays diff for multiple files', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'files' => [
                    ['path' => 'app/Models/User.php', 'diff' => '...'],
                    ['path' => 'app/Models/Post.php', 'diff' => '...'],
                ],
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('openDiffModal', 'msg_123')
            ->assertSet('diffData', function ($data) {
                return count($data['files']) === 2;
            });
    });
});

describe('Diff Display', function () {
    test('displays file path in diff viewer', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('showDiffModal', true)
            ->set('diffData', [
                'files' => [
                    ['path' => 'app/Models/User.php', 'diff' => '...'],
                ],
            ])
            ->assertSee('app/Models/User.php');
    });

    test('displays diff content with line numbers', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('showDiffModal', true)
            ->set('diffData', [
                'files' => [
                    [
                        'path' => 'test.php',
                        'diff' => '@@ -1,3 +1,5 @@',
                    ],
                ],
            ])
            ->assertSee('@@ -1,3 +1,5 @@');
    });

    test('shows empty state when no diff data', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('sessions', [])
            ->set('showDiffModal', true)
            ->set('diffData', null)
            ->assertSee('No diff available');
    });

    test('applies syntax highlighting to diff', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('showDiffModal', true)
            ->set('diffData', [
                'files' => [
                    ['path' => 'test.php', 'diff' => '+added line'],
                ],
            ])
            ->assertSeeHtml('diff-viewer');
    });
});

describe('Diff Color Coding', function () {
    test('highlights additions in green', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('showDiffModal', true)
            ->set('diffData', [
                'files' => [
                    ['path' => 'test.php', 'diff' => '+added line'],
                ],
            ])
            ->assertSeeHtml('diff-addition');
    });

    test('highlights deletions in red', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('showDiffModal', true)
            ->set('diffData', [
                'files' => [
                    ['path' => 'test.php', 'diff' => '-deleted line'],
                ],
            ])
            ->assertSeeHtml('diff-deletion');
    });

    test('shows unchanged lines in default color', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('showDiffModal', true)
            ->set('diffData', [
                'files' => [
                    ['path' => 'test.php', 'diff' => ' unchanged line'],
                ],
            ])
            ->assertSeeHtml('diff-line');
    });
});
