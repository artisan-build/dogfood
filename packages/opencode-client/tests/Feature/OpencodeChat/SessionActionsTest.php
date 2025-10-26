<?php

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    MockClient::destroyGlobal();
});

describe('Session Abort', function () {
    test('can abort active session', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['status' => 'aborted'], 200), // abortSession()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('abortSession')
            ->assertSet('successMessage', function ($message) {
                return $message !== null && str_contains($message, 'abort');
            });
    });

    test('handles abort error gracefully', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Cannot abort'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('abortSession')
            ->assertSet('error', function ($error) {
                return $error !== null;
            });
    });
});

describe('Session Summarize', function () {
    test('can summarize session', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['summary' => 'Session summary text'], 200), // summarizeSession()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('summarizeSession')
            ->assertSet('sessionSummary', function ($summary) {
                return $summary !== null && str_contains($summary, 'summary');
            });
    });

    test('handles summarize error', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Cannot summarize'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('summarizeSession')
            ->assertSet('error', function ($error) {
                return $error !== null;
            });
    });
});

describe('Session Share', function () {
    test('can share session', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'share_url' => 'https://example.com/share/abc123',
            ], 200), // shareSession()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('shareSession')
            ->assertSet('shareUrl', 'https://example.com/share/abc123')
            ->assertSet('successMessage', function ($message) {
                return $message !== null && str_contains($message, 'share');
            });
    });

    test('can unshare session', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['status' => 'unshared'], 200), // unshareSession()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('shareUrl', 'https://example.com/share/abc123')
            ->call('unshareSession')
            ->assertSet('shareUrl', null)
            ->assertSet('successMessage', function ($message) {
                return $message !== null && str_contains($message, 'unshared');
            });
    });
});

describe('Actions Dropdown Display', function () {
    test('shows actions dropdown in header when session is active', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->assertSee('Actions');
    });

    test('shows abort action in dropdown', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->assertSee('Abort Session');
    });

    test('shows summarize action in dropdown', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->assertSee('Summarize');
    });

    test('shows share action in dropdown', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->assertSee('Share Session');
    });
});
