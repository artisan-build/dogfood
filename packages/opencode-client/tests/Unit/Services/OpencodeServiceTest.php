<?php

use ArtisanBuild\OpencodeClient\Services\OpencodeService;
use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->service = new OpencodeService('http://127.0.0.1:64415');
});

describe('Client Instantiation', function () {
    test('can create OpenCode client instance', function () {
        $client = $this->service->client();

        expect($client)->toBeInstanceOf(OpenCode::class);
    });

    test('uses correct base URL', function () {
        $client = $this->service->client();

        expect($client->resolveBaseUrl())->toBe('http://127.0.0.1:64415');
    });
});

describe('Session Management', function () {
    test('can create session', function () {
        MockClient::global([
            MockResponse::make(['id' => 'ses_test123'], 200),
        ]);

        $result = $this->service->createSession();

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('id')
            ->and($result['id'])->toBe('ses_test123');
    });

    test('can list sessions', function () {
        MockClient::global([
            MockResponse::make([
                ['id' => 'ses_1', 'created_at' => '2025-10-26T12:00:00Z'],
                ['id' => 'ses_2', 'created_at' => '2025-10-26T13:00:00Z'],
            ], 200),
        ]);

        $result = $this->service->listSessions();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(2)
            ->and($result[0]['id'])->toBe('ses_1');
    });

    test('can get session details', function () {
        MockClient::global([
            MockResponse::make([
                'id' => 'ses_test123',
                'created_at' => '2025-10-26T12:00:00Z',
                'messages' => [],
            ], 200),
        ]);

        $result = $this->service->getSession('ses_test123');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('id')
            ->and($result['id'])->toBe('ses_test123');
    });

    test('can update session', function () {
        MockClient::global([
            MockResponse::make(['id' => 'ses_test123', 'updated' => true], 200),
        ]);

        $result = $this->service->updateSession('ses_test123', ['name' => 'New Name']);

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('updated')
            ->and($result['updated'])->toBeTrue();
    });

    test('can delete session', function () {
        MockClient::global([
            MockResponse::make(['deleted' => true], 200),
        ]);

        $result = $this->service->deleteSession('ses_test123');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('deleted')
            ->and($result['deleted'])->toBeTrue();
    });

    test('can fork session', function () {
        MockClient::global([
            MockResponse::make(['id' => 'ses_forked123', 'parent_id' => 'ses_test123'], 200),
        ]);

        $result = $this->service->forkSession('ses_test123', 'msg_456');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('id')
            ->and($result['id'])->toBe('ses_forked123');
    });

    test('can get session children', function () {
        MockClient::global([
            MockResponse::make([
                ['id' => 'ses_child1'],
                ['id' => 'ses_child2'],
            ], 200),
        ]);

        $result = $this->service->getSessionChildren('ses_test123');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(2);
    });

    test('can abort session', function () {
        MockClient::global([
            MockResponse::make(['aborted' => true], 200),
        ]);

        $result = $this->service->abortSession('ses_test123');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('aborted')
            ->and($result['aborted'])->toBeTrue();
    });

    test('can summarize session', function () {
        MockClient::global([
            MockResponse::make(['summary' => 'This session discussed authentication'], 200),
        ]);

        $result = $this->service->summarizeSession('ses_test123');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('summary');
    });

    test('can share session', function () {
        MockClient::global([
            MockResponse::make(['share_url' => 'https://example.com/share/xyz'], 200),
        ]);

        $result = $this->service->shareSession('ses_test123');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('share_url');
    });

    test('can unshare session', function () {
        MockClient::global([
            MockResponse::make(['unshared' => true], 200),
        ]);

        $result = $this->service->unshareSession('ses_test123');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('unshared')
            ->and($result['unshared'])->toBeTrue();
    });
});

describe('Message Operations', function () {
    test('can send prompt to session', function () {
        MockClient::global([
            MockResponse::make([
                'id' => 'msg_123',
                'role' => 'assistant',
                'parts' => [
                    ['type' => 'text', 'text' => 'Response text'],
                ],
            ], 200),
        ]);

        $result = $this->service->sendPrompt('ses_test123', 'What is 2+2?');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('id')
            ->and($result['id'])->toBe('msg_123');
    });

    test('can get session messages', function () {
        MockClient::global([
            MockResponse::make([
                ['id' => 'msg_1', 'role' => 'user'],
                ['id' => 'msg_2', 'role' => 'assistant'],
            ], 200),
        ]);

        $result = $this->service->getMessages('ses_test123');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(2);
    });

    test('can get single message', function () {
        MockClient::global([
            MockResponse::make([
                'id' => 'msg_123',
                'role' => 'user',
                'parts' => [['type' => 'text', 'text' => 'Hello']],
            ], 200),
        ]);

        $result = $this->service->getMessage('ses_test123', 'msg_123');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('id')
            ->and($result['id'])->toBe('msg_123');
    });

    test('can get session diff', function () {
        MockClient::global([
            MockResponse::make([
                'files' => [
                    [
                        'path' => 'app/Models/User.php',
                        'changes' => [
                            ['type' => 'addition', 'line' => 10, 'content' => 'protected $fillable = [];'],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $result = $this->service->getSessionDiff('ses_test123');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('files');
    });

    test('can revert message', function () {
        MockClient::global([
            MockResponse::make(['reverted' => true], 200),
        ]);

        $result = $this->service->revertMessage('ses_test123', 'msg_123');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('reverted')
            ->and($result['reverted'])->toBeTrue();
    });

    test('can unrevert message', function () {
        MockClient::global([
            MockResponse::make(['unreverted' => true], 200),
        ]);

        $result = $this->service->unrevertMessage('ses_test123', 'msg_123');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('unreverted')
            ->and($result['unreverted'])->toBeTrue();
    });
});

describe('Todo Operations', function () {
    test('can get session todos', function () {
        MockClient::global([
            MockResponse::make([
                ['id' => 'todo_1', 'text' => 'Implement authentication', 'completed' => false],
                ['id' => 'todo_2', 'text' => 'Write tests', 'completed' => true],
            ], 200),
        ]);

        $result = $this->service->getTodos('ses_test123');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(2);
    });
});

describe('Shell Operations', function () {
    test('can execute shell command', function () {
        MockClient::global([
            MockResponse::make([
                'output' => 'Command executed successfully',
                'exit_code' => 0,
            ], 200),
        ]);

        $result = $this->service->executeShell('ses_test123', 'ls -la');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('output');
    });
});

describe('File Operations', function () {
    test('can list files', function () {
        MockClient::global([
            MockResponse::make([
                ['name' => 'src', 'type' => 'directory'],
                ['name' => 'README.md', 'type' => 'file'],
            ], 200),
        ]);

        $result = $this->service->listFiles('/');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(2);
    });

    test('can read file contents', function () {
        MockClient::global([
            MockResponse::make([
                'path' => 'README.md',
                'content' => '# My Project',
            ], 200),
        ]);

        $result = $this->service->readFile('README.md');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('content');
    });

    test('can get file status', function () {
        MockClient::global([
            MockResponse::make([
                ['path' => 'app/Models/User.php', 'status' => 'modified'],
                ['path' => 'app/Models/Team.php', 'status' => 'added'],
            ], 200),
        ]);

        $result = $this->service->getFileStatus();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(2);
    });
});

describe('Search Operations', function () {
    test('can search for text', function () {
        MockClient::global([
            MockResponse::make([
                [
                    'file' => 'app/Models/User.php',
                    'line' => 23,
                    'preview' => 'public function login()',
                ],
            ], 200),
        ]);

        $result = $this->service->searchText('login');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(1);
    });

    test('can search for files', function () {
        MockClient::global([
            MockResponse::make([
                ['path' => 'app/Models/User.php'],
                ['path' => 'tests/Feature/UserTest.php'],
            ], 200),
        ]);

        $result = $this->service->searchFiles('User');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(2);
    });

    test('can search for symbols', function () {
        MockClient::global([
            MockResponse::make([
                ['name' => 'User', 'type' => 'class', 'file' => 'app/Models/User.php', 'line' => 5],
                ['name' => 'getUserById', 'type' => 'function', 'file' => 'app/Services/UserService.php', 'line' => 23],
            ], 200),
        ]);

        $result = $this->service->searchSymbols('User');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(2);
    });
});

describe('Project Operations', function () {
    test('can list projects', function () {
        MockClient::global([
            MockResponse::make([
                ['name' => 'kibble', 'path' => '/Users/ed/Projects/kibble'],
                ['name' => 'example', 'path' => '/Users/ed/Projects/example'],
            ], 200),
        ]);

        $result = $this->service->listProjects();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(2);
    });

    test('can get current project', function () {
        MockClient::global([
            MockResponse::make([
                'name' => 'kibble',
                'path' => '/Users/ed/Projects/kibble',
            ], 200),
        ]);

        $result = $this->service->getCurrentProject();

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('name');
    });

    test('can get path info', function () {
        MockClient::global([
            MockResponse::make([
                'path' => '/Users/ed/Projects/kibble/src',
                'exists' => true,
                'type' => 'directory',
            ], 200),
        ]);

        $result = $this->service->getPath('/src');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('path');
    });
});

describe('TUI Operations', function () {
    test('can submit prompt to TUI', function () {
        MockClient::global([
            MockResponse::make(['submitted' => true], 200),
        ]);

        $result = $this->service->submitTuiPrompt('What is 2+2?');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('submitted')
            ->and($result['submitted'])->toBeTrue();
    });

    test('can append to TUI prompt', function () {
        MockClient::global([
            MockResponse::make(['appended' => true], 200),
        ]);

        $result = $this->service->appendTuiPrompt(' and explain why');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('appended')
            ->and($result['appended'])->toBeTrue();
    });

    test('can clear TUI prompt', function () {
        MockClient::global([
            MockResponse::make(['cleared' => true], 200),
        ]);

        $result = $this->service->clearTuiPrompt();

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('cleared')
            ->and($result['cleared'])->toBeTrue();
    });

    test('can show toast in TUI', function () {
        MockClient::global([
            MockResponse::make(['shown' => true], 200),
        ]);

        $result = $this->service->showTuiToast('Hello from web!');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('shown')
            ->and($result['shown'])->toBeTrue();
    });

    test('can execute TUI command', function () {
        MockClient::global([
            MockResponse::make(['executed' => true], 200),
        ]);

        $result = $this->service->executeTuiCommand('agent_cycle');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('executed')
            ->and($result['executed'])->toBeTrue();
    });

    test('can open TUI themes dialog', function () {
        MockClient::global([
            MockResponse::make(['opened' => true], 200),
        ]);

        $result = $this->service->openTuiThemes();

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('opened')
            ->and($result['opened'])->toBeTrue();
    });

    test('can open TUI models dialog', function () {
        MockClient::global([
            MockResponse::make(['opened' => true], 200),
        ]);

        $result = $this->service->openTuiModels();

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('opened')
            ->and($result['opened'])->toBeTrue();
    });

    test('can open TUI help dialog', function () {
        MockClient::global([
            MockResponse::make(['opened' => true], 200),
        ]);

        $result = $this->service->openTuiHelp();

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('opened')
            ->and($result['opened'])->toBeTrue();
    });

    test('can open TUI sessions dialog', function () {
        MockClient::global([
            MockResponse::make(['opened' => true], 200),
        ]);

        $result = $this->service->openTuiSessions();

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('opened')
            ->and($result['opened'])->toBeTrue();
    });
});

describe('Error Handling', function () {
    test('handles API errors gracefully', function () {
        MockClient::global([
            MockResponse::make(['error' => 'Server unavailable'], 500),
        ]);

        $result = $this->service->createSession();

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('error');
    });

    test('returns proper error messages on failure', function () {
        MockClient::global([
            MockResponse::make(['message' => 'Invalid session ID'], 404),
        ]);

        $result = $this->service->getSession('invalid_id');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('message');
    });
});
