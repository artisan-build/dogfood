<?php

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    MockClient::destroyGlobal();
});

describe('Shell Command Execution', function () {
    test('can execute shell command', function () {
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
            ->assertSet('shellOutput', function ($output) {
                return $output !== null && str_contains($output, 'total 48');
            })
            ->assertSet('shellCommand', ''); // Input cleared after execution
    });

    test('handles shell command error', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Command failed'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('shellCommand', 'invalid-command')
            ->call('executeShellCommand')
            ->assertSet('error', function ($error) {
                return $error !== null;
            });
    });

    test('requires active session to execute commands', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('shellCommand', 'ls')
            ->call('executeShellCommand')
            ->assertSet('error', function ($error) {
                return $error !== null;
            });
    });

    test('validates command is not empty', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('shellCommand', '   ')
            ->call('executeShellCommand')
            ->assertSet('error', function ($error) {
                return $error !== null && str_contains($error, 'empty');
            });
    });
});

describe('Dangerous Commands Detection', function () {
    test('detects rm command as dangerous', function () {
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

    test('detects sudo command as dangerous', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('shellCommand', 'sudo reboot')
            ->call('executeShellCommand')
            ->assertSet('showDangerousCommandModal', true);
    });

    test('can confirm dangerous command execution', function () {
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
            ->assertSet('shellOutput', function ($output) {
                return $output !== null;
            });
    });

    test('can cancel dangerous command execution', function () {
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

describe('Shell Output Display', function () {
    test('displays shell output in chat area', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('shellOutput', 'Command executed successfully')
            ->assertSeeHtml('Command executed successfully');
    });

    test('shows loading state while executing', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        $component = Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('executingCommand', true);

        expect($component->get('executingCommand'))->toBe(true);
    });

    test('clears output when executing new command', function () {
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
            ->assertSet('shellOutput', function ($output) {
                return ! str_contains($output, 'Previous output');
            });
    });
});

describe('Shell Command UI', function () {
    test('shows shell command input when session active', function () {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->assertSeeHtml('shell-command-input');
    });

    test('shows dangerous command modal', function () {
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
