<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('Session Forking', function (): void {
    test('can fork session from message', function (): void {
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
            ->assertSet('successMessage', fn ($message) => $message !== null && str_contains($message, 'fork'));
    });

    test('handles fork error gracefully', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Cannot fork session'], 400), // forkSession() fails
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('forkSession', 'msg_123')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('switches to forked session after creation', function (): void {
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

describe('Session Children Loading', function (): void {
    test('loads child sessions when present', function (): void {
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
            ->assertSet('sessions', fn ($sessions) => count($sessions) === 3
                && $sessions[0]['id'] === 'ses_parent'
                && $sessions[1]['parent_id'] === 'ses_parent'
                && $sessions[2]['parent_id'] === 'ses_parent');
    });

    test('identifies parent-child relationships', function (): void {
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

describe('Session Hierarchy Display', function (): void {
    test('displays parent sessions at root level', function (): void {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_parent', 'name' => 'Parent Session'],
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->assertSee('Parent Session');
    });

    test('displays child sessions with indentation', function (): void {
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

    test('shows fork indicator for child sessions', function (): void {
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

describe('Fork Button Display', function (): void {
    test('fork button appears on hover for assistant messages', function (): void {
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

    test('fork button not shown for user messages', function (): void {
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

    test('fork button not shown for messages without ID', function (): void {
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

describe('Session Navigation', function (): void {
    test('can switch to child session', function (): void {
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

    test('can switch back to parent session', function (): void {
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
