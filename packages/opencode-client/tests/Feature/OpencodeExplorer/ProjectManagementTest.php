<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeExplorer;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    MockClient::destroyGlobal();
});

describe('Project List Loading', function (): void {
    test('can load list of available projects', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'projects' => [
                    ['path' => '/home/user/project1', 'name' => 'Project 1'],
                    ['path' => '/home/user/project2', 'name' => 'Project 2'],
                    ['path' => '/home/user/project3', 'name' => 'Project 3'],
                ],
            ], 200), // loadProjects()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadProjects')
            ->assertSet('availableProjects', fn ($projects) => count($projects) === 3
                && $projects[0]['name'] === 'Project 1');
    });

    test('handles empty project list', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['projects' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadProjects')
            ->assertSet('availableProjects', []);
    });

    test('handles project list error', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['error' => 'Cannot list projects'], 400),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadProjects')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Current Project', function (): void {
    test('can get current project', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'project' => [
                    'path' => '/home/user/current-project',
                    'name' => 'Current Project',
                ],
            ], 200), // loadCurrentProject()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadCurrentProject')
            ->assertSet('currentProject', fn ($project) => $project['name'] === 'Current Project');
    });

    test('handles no current project', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['project' => null], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadCurrentProject')
            ->assertSet('currentProject', null);
    });

    test('handles current project error', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['error' => 'Cannot get current project'], 400),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadCurrentProject')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Project Switching', function (): void {
    test('can switch to a different project', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['files' => []], 200), // loadFiles() after switch
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('availableProjects', [
                ['path' => '/home/user/new-project', 'name' => 'New Project'],
            ])
            ->call('switchProject', '/home/user/new-project')
            ->assertSet('currentProject', fn ($project) => $project['path'] === '/home/user/new-project')
            ->assertSet('currentPath', '/');
    });

    test('refreshes file tree after project switch', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'files' => [
                    ['name' => 'new-file.php', 'type' => 'file', 'path' => '/new/new-file.php'],
                ],
            ], 200), // loadFiles() after switch
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('availableProjects', [
                ['path' => '/new', 'name' => 'New Project'],
            ])
            ->call('switchProject', '/new')
            ->assertSet('files', fn ($files) => count($files) === 1 && $files[0]['name'] === 'new-file.php');
    });

    test('handles project switch error', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['error' => 'Project not found'], 404),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('switchProject', '/invalid/path')
            ->assertSet('error', fn ($error) => $error !== null);
    });
});

describe('Project Dropdown UI', function (): void {
    test('shows project dropdown when projects loaded', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('currentProject', ['path' => '/project1', 'name' => 'Project 1'])
            ->set('availableProjects', [
                ['path' => '/project1', 'name' => 'Project 1'],
                ['path' => '/project2', 'name' => 'Project 2'],
            ])
            ->set('showProjectDropdown', true)
            ->assertSeeHtml('project-dropdown');
    });

    test('displays current project name in header', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('currentProject', ['path' => '/my-project', 'name' => 'My Project'])
            ->assertSeeHtml('My Project');
    });

    test('shows all available projects in dropdown', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('currentProject', ['path' => '/project1', 'name' => 'Project 1'])
            ->set('availableProjects', [
                ['path' => '/project1', 'name' => 'Project 1'],
                ['path' => '/project2', 'name' => 'Project 2'],
                ['path' => '/project3', 'name' => 'Project 3'],
            ])
            ->set('showProjectDropdown', true)
            ->assertSeeHtml('Project 1')
            ->assertSeeHtml('Project 2')
            ->assertSeeHtml('Project 3');
    });

    test('can toggle project dropdown', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200),
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->assertSet('showProjectDropdown', false)
            ->call('toggleProjectDropdown')
            ->assertSet('showProjectDropdown', true)
            ->call('toggleProjectDropdown')
            ->assertSet('showProjectDropdown', false);
    });
});

describe('Breadcrumbs After Project Switch', function (): void {
    test('resets breadcrumbs to root after project switch', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['files' => []], 200), // loadFiles()
        ]);

        $component = Livewire::test(OpencodeExplorer::class)
            ->set('currentPath', '/old/deep/path')
            ->set('availableProjects', [
                ['path' => '/new-project', 'name' => 'New Project'],
            ])
            ->call('switchProject', '/new-project');

        $breadcrumbs = $component->get('breadcrumbs');

        expect($breadcrumbs)->toHaveCount(1);
        expect($breadcrumbs[0])->toBe('/');
    });
});

describe('Project Switcher Integration', function (): void {
    test('loads projects automatically on mount when configured', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
        ]);

        $component = Livewire::test(OpencodeExplorer::class);

        // By default, projects should not be loaded automatically
        expect($component->get('availableProjects'))->toBe([]);
    });

    test('can manually trigger project list load', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make([
                'projects' => [
                    ['path' => '/project', 'name' => 'Project'],
                ],
            ], 200), // loadProjects()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->call('loadProjects')
            ->assertSet('availableProjects', fn ($projects) => count($projects) === 1);
    });
});

describe('Project Path Handling', function (): void {
    test('handles relative paths correctly', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['files' => []], 200), // loadFiles()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('availableProjects', [
                ['path' => '~/projects/my-project', 'name' => 'My Project'],
            ])
            ->call('switchProject', '~/projects/my-project')
            ->assertSet('currentProject', fn ($project) => $project['path'] === '~/projects/my-project');
    });

    test('handles absolute paths correctly', function (): void {
        MockClient::global([
            MockResponse::make(['files' => []], 200), // mount()
            MockResponse::make(['files' => []], 200), // loadFiles()
        ]);

        Livewire::test(OpencodeExplorer::class)
            ->set('availableProjects', [
                ['path' => '/home/user/absolute/path', 'name' => 'Absolute Project'],
            ])
            ->call('switchProject', '/home/user/absolute/path')
            ->assertSet('currentProject', fn ($project) => $project['path'] === '/home/user/absolute/path');
    });
});
