<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('Shell Command Execution', function (): void {
    test('can execute shell command', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'command' => 'ls -la',
                'output' => 'total 48\ndrwxr-xr-x  12 user  staff   384 Oct 26 23:00 .',
                'exit_code' => 0,
            ], 200), // executeShellCommand()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('shellCommand', 'ls -la')
            ->call('executeShellCommand')
            ->assertSet('shellOutput', fn ($output) => $output !== null && str_contains($output, 'total 48'))
            ->assertSet('shellCommand', ''); // Input cleared after execution
    });

    test('handles shell command error', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Command failed'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('shellCommand', 'invalid-command')
            ->call('executeShellCommand')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('requires active session to execute commands', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('shellCommand', 'ls')
            ->call('executeShellCommand')
            ->assertSet('error', fn ($error) => $error !== null);
    });

    test('validates command is not empty', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('shellCommand', '   ')
            ->call('executeShellCommand')
            ->assertSet('error', fn ($error) => $error !== null && str_contains($error, 'empty'));
    });
});

describe('Dangerous Commands Detection', function (): void {
    test('detects rm command as dangerous', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('shellCommand', 'rm -rf /')
            ->call('executeShellCommand')
            ->assertSet('showDangerousCommandModal', true)
            ->assertSet('pendingCommand', 'rm -rf /');
    });

    test('detects sudo command as dangerous', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('shellCommand', 'sudo reboot')
            ->call('executeShellCommand')
            ->assertSet('showDangerousCommandModal', true);
    });

    test('can confirm dangerous command execution', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'command' => 'rm test.txt',
                'output' => 'File removed',
                'exit_code' => 0,
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('pendingCommand', 'rm test.txt')
            ->call('confirmDangerousCommand')
            ->assertSet('showDangerousCommandModal', false)
            ->assertSet('pendingCommand', null)
            ->assertSet('shellOutput', fn ($output) => $output !== null);
    });

    test('can cancel dangerous command execution', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('showDangerousCommandModal', true)
            ->set('pendingCommand', 'rm -rf /')
            ->call('cancelDangerousCommand')
            ->assertSet('showDangerousCommandModal', false)
            ->assertSet('pendingCommand', null);
    });
});

describe('Shell Output Display', function (): void {
    test('displays shell output in chat area', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('shellOutput', 'Command executed successfully')
            ->assertSeeHtml('Command executed successfully');
    });

    test('shows loading state while executing', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        $component = Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('executingCommand', true);

        expect($component->get('executingCommand'))->toBe(true);
    });

    test('clears output when executing new command', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'command' => 'pwd',
                'output' => '/home/user',
                'exit_code' => 0,
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('shellOutput', 'Previous output')
            ->set('shellCommand', 'pwd')
            ->call('executeShellCommand')
            ->assertSet('shellOutput', fn ($output) => ! str_contains($output, 'Previous output'));
    });
});

describe('Shell Command UI', function (): void {
    test('shows shell command input when session active', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->assertSeeHtml('shell-command-input');
    });

    test('shows dangerous command modal', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('showDangerousCommandModal', true)
            ->set('pendingCommand', 'rm -rf /')
            ->assertSeeHtml('dangerous-command-modal')
            ->assertSeeHtml('rm -rf /');
    });
});
