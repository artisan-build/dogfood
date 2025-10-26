<?php

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    MockClient::destroyGlobal();
});

describe('Session Forking', function () {
    test('can fork session from message', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'ses_forked_123',
                'parent_id' => 'ses_original_123',
                'forked_from_message' => 'msg_123',
            ], 200), // forkSession()
            MockResponse::make([
                ['id' => 'ses_original_123'],
                ['id' => 'ses_forked_123', 'parent_id' => 'ses_original_123'],
            ], 200), // loadSessions()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_original_123')
            ->call('forkSession', 'msg_123')
            ->assertSet('currentSessionId', 'ses_forked_123')
            ->assertSet('successMessage', function ($message) {
                return $message !== null && str_contains($message, 'fork');
            });
    });

    test('handles fork error gracefully', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Cannot fork session'], 400), // forkSession() fails
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('forkSession', 'msg_123')
            ->assertSet('error', function ($error) {
                return $error !== null;
            });
    });

    test('switches to forked session after creation', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'ses_forked_456',
                'parent_id' => 'ses_original_456',
            ], 200), // forkSession()
            MockResponse::make([
                ['id' => 'ses_original_456'],
                ['id' => 'ses_forked_456', 'parent_id' => 'ses_original_456'],
            ], 200), // loadSessions()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_original_456')
            ->call('forkSession', 'msg_456')
            ->assertSet('currentSessionId', 'ses_forked_456');
    });
});

describe('Session Children Loading', function () {
    test('loads child sessions when present', function () {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_parent'],
                [
                    'id' => 'ses_child1',
                    'parent_id' => 'ses_parent',
                    'forked_from_message' => 'msg_1',
                ],
                [
                    'id' => 'ses_child2',
                    'parent_id' => 'ses_parent',
                    'forked_from_message' => 'msg_2',
                ],
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->assertSet('sessions', function ($sessions) {
                return count($sessions) === 3
                    && $sessions[0]['id'] === 'ses_parent'
                    && $sessions[1]['parent_id'] === 'ses_parent'
                    && $sessions[2]['parent_id'] === 'ses_parent';
            });
    });

    test('identifies parent-child relationships', function () {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_1'],
                ['id' => 'ses_2', 'parent_id' => 'ses_1'],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeChat::class);
        $sessions = $component->get('sessions');

        expect($sessions[1])->toHaveKey('parent_id')
            ->and($sessions[1]['parent_id'])->toBe('ses_1');
    });
});

describe('Session Hierarchy Display', function () {
    test('displays parent sessions at root level', function () {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_parent', 'name' => 'Parent Session'],
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->assertSee('Parent Session');
    });

    test('displays child sessions with indentation', function () {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_parent', 'name' => 'Parent'],
                [
                    'id' => 'ses_child',
                    'parent_id' => 'ses_parent',
                    'name' => 'Child',
                ],
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->assertSee('Parent')
            ->assertSee('Child');
    });

    test('shows fork indicator for child sessions', function () {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_parent'],
                [
                    'id' => 'ses_child',
                    'parent_id' => 'ses_parent',
                    'forked_from_message' => 'msg_123',
                ],
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('sessions', [
                ['id' => 'ses_parent'],
                [
                    'id' => 'ses_child',
                    'parent_id' => 'ses_parent',
                    'forked_from_message' => 'msg_123',
                ],
            ])
            ->assertSee('Forked'); // Looking for "Forked" text indicator
    });
});

describe('Fork Button Display', function () {
    test('fork button appears on hover for assistant messages', function () {
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
            ->assertSee('Fork from here');
    });

    test('fork button not shown for user messages', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                [
                    'role' => 'user',
                    'content' => 'Test message',
                    'id' => 'msg_123',
                ],
            ])
            ->assertDontSee('Fork from here');
    });

    test('fork button not shown for messages without ID', function () {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                [
                    'role' => 'assistant',
                    'content' => 'Test message',
                    'id' => null,
                ],
            ])
            ->assertDontSee('Fork from here');
    });
});

describe('Session Navigation', function () {
    test('can switch to child session', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'ses_child',
                'parent_id' => 'ses_parent',
            ], 200), // getSession()
            MockResponse::make([], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_parent')
            ->call('switchSession', 'ses_child')
            ->assertSet('currentSessionId', 'ses_child');
    });

    test('can switch back to parent session', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'ses_parent',
            ], 200), // getSession()
            MockResponse::make([], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_child')
            ->call('switchSession', 'ses_parent')
            ->assertSet('currentSessionId', 'ses_parent');
    });
});
