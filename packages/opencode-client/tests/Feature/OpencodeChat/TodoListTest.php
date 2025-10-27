<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('Todo Panel Toggle', function (): void {
    test('can open todo panel', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('openTodoPanel')
            ->assertSet('showTodoPanel', true);
    });

    test('can close todo panel', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('showTodoPanel', true)
            ->call('closeTodoPanel')
            ->assertSet('showTodoPanel', false);
    });
});

describe('Todo Data Loading', function (): void {
    test('loads todos for current session', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                [
                    'id' => 'todo_1',
                    'content' => 'Implement feature X',
                    'completed' => false,
                ],
                [
                    'id' => 'todo_2',
                    'content' => 'Fix bug Y',
                    'completed' => true,
                ],
            ], 200), // getTodos()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('loadTodos')
            ->assertSet('todos', fn ($todos) => count($todos) === 2
                && $todos[0]['content'] === 'Implement feature X'
                && $todos[0]['completed'] === false
                && $todos[1]['content'] === 'Fix bug Y'
                && $todos[1]['completed'] === true);
    });

    test('handles empty todo list', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([], 200), // getTodos()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->call('loadTodos')
            ->assertSet('todos', []);
    });

    test('requires active session to load todos', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->call('loadTodos')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Todo Count', function (): void {
    test('computes total todo count', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        $component = Livewire::test(OpencodeChat::class)
            ->set('todos', [
                ['id' => 'todo_1', 'content' => 'Task 1', 'completed' => false],
                ['id' => 'todo_2', 'content' => 'Task 2', 'completed' => true],
                ['id' => 'todo_3', 'content' => 'Task 3', 'completed' => false],
            ]);

        expect($component->get('todoCount'))->toBe(3);
    });

    test('computes incomplete todo count', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        $component = Livewire::test(OpencodeChat::class)
            ->set('todos', [
                ['id' => 'todo_1', 'content' => 'Task 1', 'completed' => false],
                ['id' => 'todo_2', 'content' => 'Task 2', 'completed' => true],
                ['id' => 'todo_3', 'content' => 'Task 3', 'completed' => false],
            ]);

        expect($component->get('incompleteTodoCount'))->toBe(2);
    });
});

describe('Todo Display', function (): void {
    test('shows todo panel when open', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('showTodoPanel', true)
            ->set('todos', [
                ['id' => 'todo_1', 'content' => 'Task 1', 'completed' => false],
            ])
            ->assertSeeHtml('todo-panel')
            ->assertSeeHtml('Task 1');
    });

    test('displays todos as checkboxes', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('showTodoPanel', true)
            ->set('todos', [
                ['id' => 'todo_1', 'content' => 'Task 1', 'completed' => false],
                ['id' => 'todo_2', 'content' => 'Task 2', 'completed' => true],
            ])
            ->assertSeeHtml('checkbox')
            ->assertSeeHtml('Task 1')
            ->assertSeeHtml('Task 2');
    });

    test('shows completed state with strikethrough', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('showTodoPanel', true)
            ->set('todos', [
                ['id' => 'todo_1', 'content' => 'Completed Task', 'completed' => true],
            ])
            ->assertSeeHtml('line-through');
    });
});

describe('Todo Toggle', function (): void {
    test('can toggle todo completion status', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make([
                'id' => 'todo_1',
                'content' => 'Task 1',
                'completed' => true,
            ], 200), // toggleTodo()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('todos', [
                ['id' => 'todo_1', 'content' => 'Task 1', 'completed' => false],
            ])
            ->call('toggleTodo', 'todo_1')
            ->assertSet('todos', fn ($todos) => $todos[0]['completed'] === true);
    });

    test('handles toggle error gracefully', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
            MockResponse::make(['error' => 'Cannot toggle todo'], 400),
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('todos', [
                ['id' => 'todo_1', 'content' => 'Task 1', 'completed' => false],
            ])
            ->call('toggleTodo', 'todo_1')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Todo Count Display', function (): void {
    test('shows todo count badge in header', function (): void {
        MockClient::global([
            MockResponse::make([], 200), // mount()
        ]);

        Livewire::test(OpencodeChat::class)
            ->set('currentSessionId', 'ses_123')
            ->set('sessions', [])
            ->set('todos', [
                ['id' => 'todo_1', 'content' => 'Task 1', 'completed' => false],
                ['id' => 'todo_2', 'content' => 'Task 2', 'completed' => true],
                ['id' => 'todo_3', 'content' => 'Task 3', 'completed' => false],
            ])
            ->assertSeeHtml('2'); // Shows incomplete count
    });
});
