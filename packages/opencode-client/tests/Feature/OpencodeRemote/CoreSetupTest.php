<?php

use ArtisanBuild\OpencodeClient\Livewire\OpencodeRemote;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    MockClient::destroyGlobal();
});

describe('Component Mounting', function () {
    test('can mount the component', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // TUI status check
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertStatus(200);
    });

    test('initializes with default properties', function () {
        MockClient::global([
            MockResponse::make(['error' => 'Connection refused'], 500),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSet('serverUrl', 'http://127.0.0.1:64415')
            ->assertSet('tuiConnected', false)
            ->assertSet('tuiStatus', null);
    });

    test('checks TUI connection on mount', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running', 'version' => '1.0.0'], 200),
        ]);

        $component = Livewire::test(OpencodeRemote::class);

        expect($component->get('tuiConnected'))->toBeTrue();
        expect($component->get('tuiStatus'))->toBe('running');
    });
});

describe('TUI Connection Status', function () {
    test('detects when TUI is running', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount()
            MockResponse::make(['status' => 'running'], 200), // checkTuiConnection()
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection')
            ->assertSet('tuiConnected', true)
            ->assertSet('tuiStatus', 'running');
    });

    test('detects when TUI is not running', function () {
        MockClient::global([
            MockResponse::make(['error' => 'Connection refused'], 500),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection')
            ->assertSet('tuiConnected', false);
    });

    test('handles TUI connection timeout', function () {
        MockClient::global([
            MockResponse::make(['error' => 'Request timeout'], 504),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection')
            ->assertSet('tuiConnected', false)
            ->assertSet('error', function ($error) {
                return $error !== null;
            });
    });

    test('can manually refresh connection status', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount
            MockResponse::make(['status' => 'running'], 200), // manual refresh
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection')
            ->assertSet('tuiConnected', true);
    });
});

describe('Error Handling', function () {
    test('shows error message when TUI is not running', function () {
        MockClient::global([
            MockResponse::make(['error' => 'Connection refused'], 500),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection')
            ->assertSeeHtml('TUI is not running');
    });

    test('shows connection instructions when TUI is not available', function () {
        MockClient::global([
            MockResponse::make(['error' => 'Connection refused'], 500),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('Start the OpenCode TUI');
    });

    test('error message includes server URL', function () {
        MockClient::global([
            MockResponse::make(['error' => 'Connection refused'], 500),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('http://127.0.0.1:64415');
    });

    test('can clear error messages', function () {
        MockClient::global([
            MockResponse::make(['error' => 'Connection refused'], 500), // mount()
            MockResponse::make(['error' => 'Connection refused'], 500), // First checkTuiConnection() - sets error
            MockResponse::make(['status' => 'running'], 200), // Second checkTuiConnection() - clears error
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection') // Sets error
            ->assertSet('error', function ($error) {
                return $error !== null;
            })
            ->call('checkTuiConnection') // Clears and succeeds
            ->assertSet('error', null);
    });
});

describe('Layout Sections', function () {
    test('displays header with title', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('TUI Remote Control');
    });

    test('displays connection status indicator', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection')
            ->assertSeeHtml('connection-status');
    });

    test('shows connected status when TUI is running', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount()
            MockResponse::make(['status' => 'running'], 200), // checkTuiConnection()
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection')
            ->assertSeeHtml('Connected');
    });

    test('shows disconnected status when TUI is not running', function () {
        MockClient::global([
            MockResponse::make(['error' => 'Connection refused'], 500),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection')
            ->assertSeeHtml('Disconnected');
    });

    test('has main content sections container', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('remote-sections');
    });
});

describe('Projector-Optimized Styling', function () {
    test('uses large text for projector visibility', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('text-2xl');
    });

    test('has high contrast colors for visibility', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('bg-white')
            ->assertSeeHtml('dark:bg-gray-800');
    });

    test('connection status uses color coding', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount()
            MockResponse::make(['status' => 'running'], 200), // checkTuiConnection()
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection')
            ->assertSeeHtml('bg-green-100');
    });
});

describe('Refresh Functionality', function () {
    test('has refresh button for connection status', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200),
        ]);

        Livewire::test(OpencodeRemote::class)
            ->assertSeeHtml('checkTuiConnection');
    });

    test('refresh button updates connection status', function () {
        MockClient::global([
            MockResponse::make(['status' => 'running'], 200), // mount
            MockResponse::make(['error' => 'Connection refused'], 500), // first check
            MockResponse::make(['status' => 'running'], 200), // refresh
        ]);

        Livewire::test(OpencodeRemote::class)
            ->call('checkTuiConnection')
            ->assertSet('tuiConnected', false)
            ->call('checkTuiConnection')
            ->assertSet('tuiConnected', true);
    });
});
