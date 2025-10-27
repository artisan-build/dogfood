<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('Session Tree Modal', function (): void {
    test('can open tree visualization modal', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('openTreeModal')
            ->assertSet('showTreeModal', true);
    });

    test('can close tree visualization modal', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('showTreeModal', true)
            ->call('closeTreeModal')
            ->assertSet('showTreeModal', false);
    });

    test('modal button appears when sessions exist', function (): void {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_1', 'name' => 'Session 1'],
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->assertSee('View Tree');
    });
});

describe('Tree Data Structure', function (): void {
    test('builds tree data from flat session list', function (): void {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_parent', 'name' => 'Parent'],
                ['id' => 'ses_child1', 'parent_id' => 'ses_parent', 'name' => 'Child 1'],
                ['id' => 'ses_child2', 'parent_id' => 'ses_parent', 'name' => 'Child 2'],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeChat::class);
        $treeData = $component->get('treeData');

        expect($treeData)->toHaveKey('nodes')
            ->and($treeData)->toHaveKey('edges')
            ->and($treeData['nodes'])->toHaveCount(3)
            ->and($treeData['edges'])->toHaveCount(2);
    });

    test('creates nodes with correct properties', function (): void {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_1', 'name' => 'Test Session'],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeChat::class);
        $treeData = $component->get('treeData');

        $node = $treeData['nodes'][0];
        expect($node)->toHaveKey('id')
            ->and($node)->toHaveKey('label')
            ->and($node['id'])->toBe('ses_1')
            ->and($node['label'])->toBe('Test Session');
    });

    test('creates edges for parent-child relationships', function (): void {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_parent'],
                ['id' => 'ses_child', 'parent_id' => 'ses_parent'],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeChat::class);
        $treeData = $component->get('treeData');

        $edge = $treeData['edges'][0];
        expect($edge)->toHaveKey('from')
            ->and($edge)->toHaveKey('to')
            ->and($edge['from'])->toBe('ses_parent')
            ->and($edge['to'])->toBe('ses_child');
    });

    test('highlights current session in tree', function (): void {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_1', 'name' => 'Session 1'],
                ['id' => 'ses_2', 'name' => 'Session 2'],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_1');

        $treeData = $component->get('treeData');
        $currentNode = collect($treeData['nodes'])->firstWhere('id', 'ses_1');

        expect($currentNode)->toHaveKey('color')
            ->and($currentNode['color'])->toBe('#3b82f6'); // blue-500
    });

    test('handles sessions with no parent', function (): void {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_1'],
                ['id' => 'ses_2'],
            ], 200),
        ]);

        $component = Livewire::test(OpencodeChat::class);
        $treeData = $component->get('treeData');

        expect($treeData['edges'])->toBeEmpty();
    });
});

describe('Tree Navigation', function (): void {
    test('can navigate to session from tree node click', function (): void {
        MockClient::global([
            MockResponse::make([
                ['id' => 'ses_1'],
                ['id' => 'ses_2'],
            ], 200), // mount()
            MockResponse::make([
                'id' => 'ses_2',
            ], 200), // getSession()
            MockResponse::make([], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_1')
            ->call('navigateToSessionFromTree', 'ses_2')
            ->assertSet('currentSessionId', 'ses_2')
            ->assertSet('showTreeModal', false);
    });

    test('closes modal after navigation', function (): void {
        MockClient::global([
            MockResponse::make([
                ['id' => 'ses_1'],
            ], 200), // mount()
            MockResponse::make([
                'id' => 'ses_1',
            ], 200), // getSession()
            MockResponse::make([], 200), // getMessages()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('showTreeModal', true)
            ->call('navigateToSessionFromTree', 'ses_1')
            ->assertSet('showTreeModal', false);
    });
});

describe('Tree Display', function (): void {
    test('displays tree container in modal', function (): void {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_1', 'name' => 'Session 1'],
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('showTreeModal', true)
            ->assertSeeHtml('id="session-tree"');
    });

    test('shows empty state when no sessions', function (): void {
        MockClient::global([
            '*' => MockResponse::make([], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('showTreeModal', true)
            ->assertSee('No sessions to display');
    });

    test('shows session count in modal header', function (): void {
        MockClient::global([
            '*' => MockResponse::make([
                ['id' => 'ses_1'],
                ['id' => 'ses_2'],
                ['id' => 'ses_3'],
            ], 200),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('showTreeModal', true)
            ->assertSee('3 sessions');
    });
});
