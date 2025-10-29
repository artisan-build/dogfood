<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('Message Revert', function (): void {
    test('can revert a message', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'msg_123',
                'reverted' => true,
            ], 200), // revertMessage()
            MockResponse::make([], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('revertMessage', 'msg_123')
            ->assertSet('successMessage', fn ($message) => $message !== null && str_contains($message, 'reverted'));
    });

    test('handles revert error gracefully', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Cannot revert message'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('revertMessage', 'msg_123')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('reloads messages after revert', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'msg_123', 'reverted' => true], 200), // revertMessage()
            MockResponse::make([
                [
                    'role' => 'assistant',
                    'parts' => [['type' => 'text', 'text' => 'Test']],
                    'id' => 'msg_123',
                    'reverted' => true,
                ],
            ], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('revertMessage', 'msg_123')
            ->assertSet('messages', fn ($messages) => isset($messages[0]['reverted']) && $messages[0]['reverted'] === true);
    });
});

describe('Message Unrevert', function (): void {
    test('can unrevert a message', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'msg_123',
                'reverted' => false,
            ], 200), // unrevertMessage()
            MockResponse::make([], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('unrevertMessage', 'msg_123')
            ->assertSet('successMessage', fn ($message) => $message !== null && str_contains($message, 'unreverted'));
    });

    test('handles unrevert error gracefully', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Cannot unrevert message'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('unrevertMessage', 'msg_123')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('reloads messages after unrevert', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'msg_123', 'reverted' => false], 200), // unrevertMessage()
            MockResponse::make([
                [
                    'role' => 'assistant',
                    'parts' => [['type' => 'text', 'text' => 'Test']],
                    'id' => 'msg_123',
                    'reverted' => false,
                ],
            ], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('unrevertMessage', 'msg_123')
            ->assertSet('messages', fn ($messages) => isset($messages[0]['reverted']) && $messages[0]['reverted'] === false);
    });
});

describe('Revert Button Display', function (): void {
    test('revert button appears on non-reverted messages', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                [
                    'role' => 'assistant',
                    'content' => 'Test',
                    'id' => 'msg_123',
                    'reverted' => false,
                ],
            ])
            ->assertSeeHtml('Revert');
    });

    test('unrevert button appears on reverted messages', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                [
                    'role' => 'assistant',
                    'content' => 'Test',
                    'id' => 'msg_123',
                    'reverted' => true,
                ],
            ])
            ->assertSeeHtml('Unrevert');
    });

    test('revert button not shown for user messages', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                [
                    'role' => 'user',
                    'content' => 'Test',
                    'id' => 'msg_123',
                ],
            ])
            ->assertDontSee('Revert');
    });
});

describe('Reverted State Display', function (): void {
    test('shows visual indicator for reverted messages', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                [
                    'role' => 'assistant',
                    'content' => 'Test',
                    'id' => 'msg_123',
                    'reverted' => true,
                ],
            ])
            ->assertSee('Reverted');
    });

    test('applies strikethrough styling to reverted messages', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                [
                    'role' => 'assistant',
                    'content' => 'Test',
                    'id' => 'msg_123',
                    'reverted' => true,
                ],
            ])
            ->assertSeeHtml('line-through');
    });

    test('shows opacity reduction for reverted messages', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('messages', [
                [
                    'role' => 'assistant',
                    'content' => 'Test',
                    'id' => 'msg_123',
                    'reverted' => true,
                ],
            ])
            ->assertSeeHtml('opacity-50');
    });
});
