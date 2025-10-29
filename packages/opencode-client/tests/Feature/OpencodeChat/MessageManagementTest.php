<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('Message Display', function (): void {
    test('displays user messages with correct styling', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                ['role' => 'user', 'content' => 'Hello, assistant!'],
            ])
            ->assertSee('Hello, assistant!')
            ->assertSee('You');
    });

    test('displays assistant messages with correct styling', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                ['role' => 'assistant', 'content' => 'Hello, user!'],
            ])
            ->assertSee('Hello, user!')
            ->assertSee('Assistant');
    });

    test('displays multiple messages in order', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                ['role' => 'user', 'content' => 'First message'],
                ['role' => 'assistant', 'content' => 'Second message'],
                ['role' => 'user', 'content' => 'Third message'],
            ])
            ->assertSee('First message')
            ->assertSee('Second message')
            ->assertSee('Third message');
    });
});

describe('Message History Loading', function (): void {
    test('loads message history when switching to session', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'ses_123'], 200), // getSession()
            MockResponse::make([
                [
                    'id' => 'msg_1',
                    'role' => 'user',
                    'parts' => [['type' => 'text', 'text' => 'Hello']],
                    'timestamp' => '2025-10-26T12:00:00Z',
                ],
                [
                    'id' => 'msg_2',
                    'role' => 'assistant',
                    'parts' => [['type' => 'text', 'text' => 'Hi there']],
                    'timestamp' => '2025-10-26T12:00:05Z',
                ],
            ], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('switchSession', 'ses_123')
            ->assertSet('messages', fn ($messages) => count($messages) === 2
                && $messages[0]['role'] === 'user'
                && $messages[0]['content'] === 'Hello'
                && $messages[1]['role'] === 'assistant'
                && $messages[1]['content'] === 'Hi there');
    });

    test('transforms API message format to display format', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'ses_123'], 200), // getSession()
            MockResponse::make([
                [
                    'id' => 'msg_1',
                    'role' => 'user',
                    'parts' => [
                        ['type' => 'text', 'text' => 'Message content'],
                    ],
                ],
            ], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('switchSession', 'ses_123')
            ->assertSet('messages.0.content', 'Message content')
            ->assertSet('messages.0.role', 'user');
    });

    test('handles messages with multiple parts', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'ses_123'], 200), // getSession()
            MockResponse::make([
                [
                    'id' => 'msg_1',
                    'role' => 'assistant',
                    'parts' => [
                        ['type' => 'other', 'data' => 'ignored'],
                        ['type' => 'text', 'text' => 'First text part'],
                        ['type' => 'text', 'text' => 'Second text part (ignored)'],
                    ],
                ],
            ], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('switchSession', 'ses_123')
            ->assertSet('messages.0.content', 'First text part');
    });

    test('handles empty message history', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'ses_123'], 200), // getSession()
            MockResponse::make([], 200), // getMessages() returns empty
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('switchSession', 'ses_123')
            ->assertSet('messages', []);
    });
});

describe('Sending Messages', function (): void {
    test('can send message to active session', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'msg_response',
                'role' => 'assistant',
                'parts' => [['type' => 'text', 'text' => 'Response from AI']],
            ], 200), // sendPrompt()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messageInput', 'Test message')
            ->call('sendMessage')
            ->assertSet('messageInput', '') // Input cleared
            ->assertSet('messages', fn ($messages) => count($messages) === 2 // user message + AI response
                && $messages[0]['role'] === 'user'
                && $messages[0]['content'] === 'Test message'
                && $messages[1]['role'] === 'assistant'
                && $messages[1]['content'] === 'Response from AI');
    });

    test('validates message is not empty', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messageInput', '   ')
            ->call('sendMessage')
            ->assertSet('error', fn ($error) => $error !== null && str_contains($error, 'empty'));
    });

    test('requires active session to send message', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', null)
            ->set('messageInput', 'Test message')
            ->call('sendMessage')
            ->assertSet('error', fn ($error) => $error !== null && str_contains($error, 'session'));
    });

    test('shows sending state while processing', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        $component = Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sending', true);

        expect($component->get('sending'))->toBeTrue();
    });

    test('handles message sending error', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'API error'], 500), // sendPrompt() fails
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messageInput', 'Test message')
            ->call('sendMessage')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Message UI States', function (): void {
    test('shows empty state when no messages', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [])
            ->assertSee('Start the conversation');
    });

    test('shows no active session state when session is null', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', null)
            ->assertSee('No active session');
    });

    test('shows thinking indicator while sending', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                ['role' => 'user', 'content' => 'Test message'],
            ])
            ->set('sending', true)
            ->assertSee('Thinking...');
    });
});
