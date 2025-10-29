<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeRemote;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('Submit Prompt', function (): void {
    test('can submit prompt to TUI', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount()
            MockResponse::make(['success' => true], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('promptText', 'Write a function to calculate fibonacci')
            ->call('submitPrompt')
            ->assertSet('promptText', '') // Should clear after submit
            ->assertSet('success', fn ($success) => $success !== null);
    });

    test('validates prompt is not empty before submitting', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount()
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('promptText', '')
            ->call('submitPrompt')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('handles submit prompt error', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount()
            MockResponse::make(['error' => 'TUI not responding'], 500),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('promptText', 'test prompt')
            ->call('submitPrompt')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('sets loading state during submit', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount()
            MockResponse::make(['success' => true], 200),
        ]);

        $component = Livewire::test(OpencodeRemote::class)
            ->set('promptText', 'test')
            ->assertSet('isSubmittingPrompt', false);

        // Note: We can't easily test the loading state mid-execution
        // but we can verify it's false after completion
        $component->call('submitPrompt')
            ->assertSet('isSubmittingPrompt', false);
    });
});

describe('Append Prompt', function (): void {
    test('can append text to TUI prompt', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount()
            MockResponse::make(['success' => true], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('appendText', 'Add more details')
            ->call('appendPrompt')
            ->assertSet('appendText', '') // Should clear after append
            ->assertSet('success', fn ($success) => $success !== null);
    });

    test('validates append text is not empty', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('appendText', '')
            ->call('appendPrompt')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('handles append prompt error', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount()
            MockResponse::make(['error' => 'Append failed'], 500),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('appendText', 'test text')
            ->call('appendPrompt')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('sets loading state during append', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
            MockResponse::make(['success' => true], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('appendText', 'test')
            ->assertSet('isAppendingPrompt', false)
            ->call('appendPrompt')
            ->assertSet('isAppendingPrompt', false);
    });
});

describe('Clear Prompt', function (): void {
    test('can clear TUI prompt', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount()
            MockResponse::make(['success' => true], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('clearPrompt')
            ->assertSet('success', fn ($success) => $success !== null);
    });

    test('handles clear prompt error', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
            MockResponse::make(['error' => 'Clear failed'], 500),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('clearPrompt')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('sets loading state during clear', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
            MockResponse::make(['success' => true], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSet('isClearingPrompt', false)
            ->call('clearPrompt')
            ->assertSet('isClearingPrompt', false);
    });
});

describe('Prompt UI', function (): void {
    test('displays prompt input section', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('prompt-section');
    });

    test('has submit prompt button', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('submitPrompt');
    });

    test('has append prompt button', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('appendPrompt');
    });

    test('has clear prompt button', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('clearPrompt');
    });

    test('shows loading state for submit button', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('isSubmittingPrompt', true)
            ->assertSeeHtml('Submitting');
    });

    test('shows loading state for append button', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('isAppendingPrompt', true)
            ->assertSeeHtml('Appending');
    });

    test('shows loading state for clear button', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('isClearingPrompt', true)
            ->assertSeeHtml('Clearing');
    });

    test('shows success message after operation', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
            MockResponse::make(['success' => true], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->set('promptText', 'test')
            ->call('submitPrompt')
            ->assertSeeHtml('Prompt submitted successfully');
    });
});

describe('Prompt Section Visibility', function (): void {
    test('shows prompt section when TUI is connected', function (): void {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('prompt-section');
    });

    test('hides prompt section when TUI is disconnected', function (): void {
        MockClient::global([
            MockResponse::make(['error' => 'Connection refused'], 500),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertDontSeeHtml('prompt-section');
    });
});
