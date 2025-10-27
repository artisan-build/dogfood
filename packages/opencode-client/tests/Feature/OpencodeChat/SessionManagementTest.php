<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    // Set up mock client globally
    MockClient::destroyGlobal();
});

describe('Component Rendering', function (): void {
    test('can render chat component', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->assertStatus(200)
            ->assertSee('OpenCode Chat');
    });
});

describe('Session List', function (): void {
    test('can load session list', function (): void {
        MockClient::global([
            // Use wildcard to match any request
            '*' => MockResponse::make([
                ['id' => 'ses_1', 'created_at' => '2025-10-26T12:00:00Z'],
                ['id' => 'ses_2', 'created_at' => '2025-10-26T13:00:00Z'],
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->assertSet('sessions', fn ($sessions) => count($sessions) === 2
                && $sessions[0]['id'] === 'ses_1'
                && $sessions[1]['id'] === 'ses_2');
    });

    test('displays error when session list fails to load', function (): void {
        MockClient::global([
            '*' => MockResponse::make(['error' => 'Server unavailable'], 500),
        ]);

        Livewire::test(OpencodeChat::class)
            ->assertSet('error', fn ($error) => $error !== null && str_contains($error, 'Server unavailable'));
    });

    test('session list is empty when API returns empty array', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->assertSet('sessions', []);
    });
});

describe('Session Creation', function (): void {
    test('can create new session', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'ses_new123'], 200), // createSession()
            MockResponse::make([['id' => 'ses_new123']], 200), // loadSessions()
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('createNewSession')
            ->assertSet('currentSessionId', 'ses_new123')
            ->assertSet('error', null);
    });

    test('displays success message after creating session', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'ses_new123'], 200), // createSession()
            MockResponse::make([['id' => 'ses_new123']], 200), // loadSessions()
        ]);

        $component = Livewire::test(OpencodeChat::class)
            ->call('createNewSession');

        $successMessage = $component->get('successMessage');
        expect($successMessage)->toBeString()
            ->and($successMessage)->toContain('created');
    });

    test('handles session creation error', function (): void {
        MockClient::global([
            '*' => MockResponse::make(['error' => 'Failed to create session'], 500),
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('createNewSession')
            ->assertSet('currentSessionId', null)
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Session Switching', function (): void {
    test('can switch to different session', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'ses_switch123',
                'created_at' => '2025-10-26T12:00:00Z',
                'messages' => [],
            ], 200), // getSession()
            MockResponse::make([], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('switchSession', 'ses_switch123')
            ->assertSet('currentSessionId', 'ses_switch123')
            ->assertSet('error', null);
    });

    test('clears messages when switching sessions', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'ses_switch123',
                'messages' => [],
            ], 200), // getSession()
            MockResponse::make([], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('messages', [
                ['role' => 'user', 'content' => 'Old message'],
            ])
            ->call('switchSession', 'ses_switch123')
            ->assertSet('messages', []);
    });

    test('loads session messages after switching', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'ses_switch123',
                'messages' => [],
            ], 200), // getSession()
            MockResponse::make([
                ['id' => 'msg_1', 'role' => 'user', 'parts' => [['type' => 'text', 'text' => 'Hello']]],
                ['id' => 'msg_2', 'role' => 'assistant', 'parts' => [['type' => 'text', 'text' => 'Hi there']]],
            ], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('switchSession', 'ses_switch123')
            ->assertSet('messages', fn ($messages) => count($messages) === 2);
    });

    test('handles session switch error', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Session not found'], 404), // getSession()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_old123')
            ->call('switchSession', 'ses_invalid')
            ->assertSet('currentSessionId', 'ses_old123') // Should not change
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Session Deletion', function (): void {
    test('can delete session', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['deleted' => true], 200), // deleteSession()
            MockResponse::make([
                ['id' => 'ses_1'],
            ], 200), // loadSessions()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('sessions', [
                ['id' => 'ses_1'],
                ['id' => 'ses_2'],
            ])
            ->call('deleteSession', 'ses_2')
            ->assertSet('sessions', fn ($sessions) => count($sessions) === 1 && $sessions[0]['id'] === 'ses_1');
    });

    test('displays success message after deletion', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['deleted' => true], 200), // deleteSession()
            MockResponse::make([], 200), // loadSessions()
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('deleteSession', 'ses_delete123')
            ->assertSet('successMessage', fn ($message) => $message !== null && str_contains($message, 'deleted'));
    });

    test('handles deletion error', function (): void {
        MockClient::global([
            '*' => MockResponse::make(['error' => 'Cannot delete active session'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('deleteSession', 'ses_active123')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('clears current session if deleting active session', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['deleted' => true], 200), // deleteSession()
            MockResponse::make([], 200), // loadSessions()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_delete123')
            ->call('deleteSession', 'ses_delete123')
            ->assertSet('currentSessionId', null);
    });
});

describe('Session Renaming', function (): void {
    test('can rename session', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'ses_rename123', 'name' => 'New Name', 'updated' => true], 200), // updateSession()
            MockResponse::make([
                ['id' => 'ses_rename123', 'name' => 'New Name'],
            ], 200), // loadSessions()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('sessions', [
                ['id' => 'ses_rename123', 'name' => 'Old Name'],
            ])
            ->call('renameSession', 'ses_rename123', 'New Name')
            ->assertSet('sessions.0.name', 'New Name');
    });

    test('displays success message after renaming', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['updated' => true], 200), // updateSession()
            MockResponse::make([], 200), // loadSessions()
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('renameSession', 'ses_rename123', 'New Name')
            ->assertSet('successMessage', fn ($message) => $message !== null && str_contains($message, 'renamed'));
    });

    test('validates session name is not empty', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('renameSession', 'ses_123', '')
            ->assertSet('error', fn ($error) => $error !== null && str_contains($error, 'empty'));
    });

    test('handles rename error', function (): void {
        MockClient::global([
            '*' => MockResponse::make(['error' => 'Session not found'], 404),
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('renameSession', 'ses_invalid', 'New Name')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('UI State Management', function (): void {
    test('shows active session with visual indicator', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_active123')
            ->set('sessions', [
                ['id' => 'ses_active123', 'name' => 'Active Session'],
                ['id' => 'ses_other456', 'name' => 'Other Session'],
            ])
            ->assertSee('Active Session')
            ->assertSee('Other Session');
    });

    test('session list updates after create', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'ses_new123'], 200), // createSession()
            MockResponse::make([
                ['id' => 'ses_new123'],
            ], 200), // loadSessions()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('sessions', [])
            ->call('createNewSession')
            ->assertSet('sessions', fn ($sessions) => count($sessions) === 1);
    });

    test('session list updates after delete', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['deleted' => true], 200), // deleteSession()
            MockResponse::make([
                ['id' => 'ses_1'],
            ], 200), // loadSessions()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('sessions', [
                ['id' => 'ses_1'],
                ['id' => 'ses_2'],
            ])
            ->call('deleteSession', 'ses_2')
            ->assertSet('sessions', fn ($sessions) => count($sessions) === 1);
    });
});
