<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('Permission State Management', function (): void {
    test('can set pending permissions manually', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('pendingPermissions', [
                [
                    'id' => 'perm_1',
                    'type' => 'file_write',
                    'resource' => '/path/to/file.txt',
                    'status' => 'pending',
                ],
            ])
            ->assertSet('pendingPermissions', fn ($permissions) => count($permissions) === 1
                && $permissions[0]['type'] === 'file_write');
    });

    test('handles empty permission list', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('pendingPermissions', [])
            ->assertSet('pendingPermissions', []);
    });
});

describe('Permission Approval', function (): void {
    test('can approve permission', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'perm_1',
                'status' => 'approved',
            ], 200), // approvePermission()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('pendingPermissions', [
                ['id' => 'perm_1', 'type' => 'file_write', 'resource' => '/file.txt', 'status' => 'pending'],
            ])
            ->call('approvePermission', 'perm_1')
            ->assertSet('pendingPermissions', function ($permissions) {
                return count($permissions) === 0; // Permission removed from pending list
            });
    });

    test('handles approval error', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Cannot approve permission'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('pendingPermissions', [
                ['id' => 'perm_1', 'type' => 'file_write', 'resource' => '/file.txt', 'status' => 'pending'],
            ])
            ->call('approvePermission', 'perm_1')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Permission Denial', function (): void {
    test('can deny permission', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'perm_1',
                'status' => 'denied',
            ], 200), // denyPermission()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('pendingPermissions', [
                ['id' => 'perm_1', 'type' => 'file_write', 'resource' => '/file.txt', 'status' => 'pending'],
            ])
            ->call('denyPermission', 'perm_1')
            ->assertSet('pendingPermissions', function ($permissions) {
                return count($permissions) === 0; // Permission removed from pending list
            });
    });

    test('handles denial error', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Cannot deny permission'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('pendingPermissions', [
                ['id' => 'perm_1', 'type' => 'file_write', 'resource' => '/file.txt', 'status' => 'pending'],
            ])
            ->call('denyPermission', 'perm_1')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Permission Modal Display', function (): void {
    test('shows permission modal when permissions pending', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('pendingPermissions', [
                ['id' => 'perm_1', 'type' => 'file_write', 'resource' => '/file.txt', 'status' => 'pending'],
            ])
            ->assertSeeHtml('permission-modal')
            ->assertSeeHtml('/file.txt');
    });

    test('displays permission type and resource', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('pendingPermissions', [
                [
                    'id' => 'perm_1',
                    'type' => 'command_execute',
                    'resource' => 'rm -rf /tmp/*',
                    'status' => 'pending',
                ],
            ])
            ->assertSeeHtml('command_execute')
            ->assertSeeHtml('rm -rf /tmp/*');
    });

    test('shows approve and deny buttons', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('pendingPermissions', [
                ['id' => 'perm_1', 'type' => 'file_write', 'resource' => '/file.txt', 'status' => 'pending'],
            ])
            ->assertSeeHtml('Approve')
            ->assertSeeHtml('Deny');
    });
});

describe('Multiple Permissions', function (): void {
    test('handles multiple pending permissions', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('pendingPermissions', [
                ['id' => 'perm_1', 'type' => 'file_write', 'resource' => '/file1.txt', 'status' => 'pending'],
                ['id' => 'perm_2', 'type' => 'file_write', 'resource' => '/file2.txt', 'status' => 'pending'],
                ['id' => 'perm_3', 'type' => 'command_execute', 'resource' => 'git push', 'status' => 'pending'],
            ])
            ->assertSeeHtml('/file1.txt')
            ->assertSeeHtml('/file2.txt')
            ->assertSeeHtml('git push');
    });

    test('approving one permission keeps others pending', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['id' => 'perm_1', 'status' => 'approved'], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('pendingPermissions', [
                ['id' => 'perm_1', 'type' => 'file_write', 'resource' => '/file1.txt', 'status' => 'pending'],
                ['id' => 'perm_2', 'type' => 'file_write', 'resource' => '/file2.txt', 'status' => 'pending'],
            ])
            ->call('approvePermission', 'perm_1')
            ->assertSet('pendingPermissions', fn ($permissions) => count($permissions) === 1 && $permissions[0]['id'] === 'perm_2');
    });
});

describe('Permission Badge Counter', function (): void {
    test('computes pending permission count', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        $component = Livewire::test(OpencodeChat::class)
            ->set('pendingPermissions', [
                ['id' => 'perm_1', 'type' => 'file_write', 'resource' => '/file1.txt', 'status' => 'pending'],
                ['id' => 'perm_2', 'type' => 'file_write', 'resource' => '/file2.txt', 'status' => 'pending'],
                ['id' => 'perm_3', 'type' => 'command_execute', 'resource' => 'git push', 'status' => 'pending'],
            ]);

        expect($component->get('pendingPermissionCount'))->toBe(3);
    });

    test('shows permission count badge when permissions pending', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('pendingPermissions', [
                ['id' => 'perm_1', 'type' => 'file_write', 'resource' => '/file.txt', 'status' => 'pending'],
                ['id' => 'perm_2', 'type' => 'file_write', 'resource' => '/file2.txt', 'status' => 'pending'],
            ])
            ->assertSeeHtml('2'); // Badge shows count
    });
});
