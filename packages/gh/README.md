# GH

This package wraps the [GitHub CLI](https://cli.github.com) for easy use in Laravel. We only use this as a dev
dependency and everyone on our team has the GitHub CLI installed and authenticated locally. Using this as a non-dev
dependency and managing the authentication on a remote server is beyond the scope of this documentation.

## Installation

`composer require artisan-build/gh`

## Configuration

>[Warning]
> The default configuration file is optimized for how we use this package. It is set up to do some
> risky things like archive or delete repositories without confirmation. Carefully read the GitHub CLI
> documentation and edit the configuration file to meet your organization's needs

## Usage

We've basically just created a fluid wrapper around the GitHub CLI, using the Laravel Process facade to actually
run the commands. Below is a sample of what can be done. Please refer to the tests and the GitHub CLI documentation
for a fuller view of what is possible with this package.

### Auth
```php
// Login using web
GH::auth()->login(null, null, true);

// Logout
GH::auth()->logout('github.com');

// Check status
GH::auth()->status();
```

### Browse

```php
// Open a repository
GH::browse('username/repository')->open();

// Open a specific branch
GH::browse('username/repository')->open('main');

// Open a specific commit
GH::browse('username/repository')->open(null, 'abc123');
```

### Cache

```php
// List caches
GH::cache()->list();

// Delete a cache
GH::cache()->delete('cache-key-123');

// Restore a cache
GH::cache()->restore('cache-key-123');
```

### Codespace

```php
// Create a codespace
GH::codespace()->create('username/repository', 'main', 'standardLinux');

// Stop a codespace
GH::codespace()->stop('codespace-name');

// View logs
GH::codespace()->logs('codespace-name');
```

### Config

```php
// Get a configuration value
GH::config()->get('editor');

// Set a configuration value
GH::config()->set('editor', 'vim');

// List all configuration values
GH::config()->list();
```

### Gist

```php
// Create a gist
GH::gist()->create(['file1.txt'], 'Test Gist', true);

// View a gist
GH::gist()->view('gist-id', true);

// Delete a gist
GH::gist()->delete('gist-id');
```

### GPG Key

```php
// Add a GPG key
GH::gpgKey()->add('path/to/key.gpg');

// List GPG keys
GH::gpgKey()->list();

// Delete a GPG key
GH::gpgKey()->delete('12345');
```

### Issue

```php
// Create a new issue
GH::issue('username/repository')->create('Issue Title', 'Issue Body', ['--label bug']);

// Edit an issue
GH::issue('username/repository')->edit('123', 'Updated Title', 'Updated Body');

// Close and reopen an issue
GH::issue('username/repository')->close('123');
GH::issue('username/repository')->reopen('123');

// Comment on an issue
GH::issue('username/repository')->comment('123', 'This is a comment.');
```

### Label

```php
// Create a label
GH::label('username/repository')->create('bug', 'Indicates a bug', 'FF0000');

// Edit a label
GH::label('username/repository')->edit('bug', 'fixed-bug', 'Resolved', '00FF00');

// Delete a label
GH::label('username/repository')->delete('bug');
```

### Organization

```php
// View organization details
GH::org('organization-name')->view();

// List members
GH::org('organization-name')->members();

// List pending invitations
GH::org('organization-name')->invitations();
```

### Pull Request

```php
// Create a new pull request
GH::pr('username/repository')->create('PR Title', 'PR Body', ['--base main', '--draft']);

// Edit a pull request
GH::pr('username/repository')->edit('123', 'Updated Title', 'Updated Body');

// Merge a pull request
GH::pr('username/repository')->merge('123', true); // Squash merge

// Close and reopen a pull request
GH::pr('username/repository')->close('123');
GH::pr('username/repository')->reopen('123');
```

### Project

```php
// List projects
GH::project('organization-name')->list();

// View project details
GH::project('organization-name')->view(123);

// Create a project
GH::project('organization-name')->create('New Project');

// Delete a project
GH::project('organization-name')->delete(123);
```

### Release

```php
// Create a release
GH::release('username/repository')->create('v1.0.0', 'Release Title', 'Release Notes');

// Upload an asset to a release
GH::release('username/repository')->uploadAsset('v1.0.0', 'path/to/file.zip', 'Asset Label');

// Delete a release
GH::release('username/repository')->delete('v1.0.0');
```

### Repository

```php
// Create a new repository
GH::repo('username/repository')->create();

// Clone a repository
GH::repo('username/repository')->clone('directory', 'https');

// Archive a repository
GH::repo('username/repository')->archive();
```

### Ruleset

```php
// Create a ruleset
GH::ruleset('username/repository')->create([
    'name' => 'My Ruleset',
    'enforcement' => 'active',
]);

// Enable a ruleset
GH::ruleset('username/repository')->enable('123');
```

### Workflow Run
```php
// List workflow runs
GH::run('username/repository')->list();

// View a workflow run
GH::run('username/repository')->view('123');

// Rerun a workflow
GH::run('username/repository')->rerun('123');
```

### Search

```php
// Search repositories
GH::search()->repos('laravel');

// Search issues
GH::search()->issues('bug label:bug');
```

### Secret

```php
// Set a secret
GH::secret('username/repository')->set('MY_SECRET', 'secret-value');

// Delete a secret
GH::secret('username/repository')->delete('MY_SECRET');
```

### Status

```php
// View status
GH::status()->show();
```

### Variable

```php
// List variables
GH::variable('username/repository')->list();

// Set a variable
GH::variable('username/repository')->set('ENV_VAR', 'value123');
```

### Workflow

```php
// Enable a workflow
GH::workflow('username/repository')->enable('workflow.yml');

// Disable a workflow
GH::workflow('username/repository')->disable('workflow.yml');
```


## Memberware

This package is part of our internal toolkit and is optimized for our own purposes. We do not accept issues or PRs
in this repository. 

