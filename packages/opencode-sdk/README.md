<p align="center"><img src="https://github.com/artisan-build/opencode-sdk/raw/HEAD/art/opencode-sdk.png" width="75%" alt="Artisan Build Package Opencode Sdk Logo"></p>

# OpenCode SDK

A PHP SDK for interacting with the OpenCode API. Built with [Saloon](https://docs.saloon.dev/), this package provides a clean, type-safe interface for managing OpenCode sessions, sending messages, and interacting with the OpenCode API programmatically.

> [!WARNING]
> This package is currently under active development, and we have not yet released a major version. Once a 0.* version
> has been tagged, we strongly recommend locking your application to a specific working version because we might make
> breaking changes even in patch releases until we've tagged 1.0.

## Features

- **Complete API Coverage**: Auto-generated from OpenAPI spec with 52+ endpoints
- **Type-Safe DTOs**: 85+ data transfer objects for request/response handling
- **Laravel Integration**: Service provider with container binding and configuration
- **Session Management**: Create, update, delete, and manage OpenCode sessions
- **Message Handling**: Send prompts and retrieve AI assistant responses
- **File Operations**: List, read, and check file status
- **Configuration Management**: Get and update OpenCode configuration
- **Event Subscriptions**: Subscribe to OpenCode events

## Installation

Install the package via Composer:

```bash
composer require artisan-build/opencode-sdk
```

### Publish Configuration (Optional)

Publish the configuration file to customize settings:

```bash
php artisan vendor:publish --tag=opencode-sdk
```

This creates `config/opencode-sdk.php` in your application.

## Configuration

The package works out of the box with sensible defaults. Configuration can be customized via environment variables or by publishing the config file.

### Environment Variables

```env
# OpenCode API Base URL (default: http://localhost:3333)
OPENCODE_BASE_URL=http://localhost:3333

# Request timeout in seconds (default: 30)
OPENCODE_TIMEOUT=30

# Optional authentication
OPENCODE_AUTH_TYPE=bearer
OPENCODE_AUTH_TOKEN=your-token-here

# Retry configuration
OPENCODE_RETRY_TIMES=3
OPENCODE_RETRY_SLEEP=100
```

### Configuration File

After publishing, you can customize `config/opencode-sdk.php`:

```php
return [
    'base_url' => env('OPENCODE_BASE_URL', 'http://localhost:3333'),
    'timeout' => (int) env('OPENCODE_TIMEOUT', 30),
    'auth' => [
        'type' => env('OPENCODE_AUTH_TYPE'),
        'token' => env('OPENCODE_AUTH_TOKEN'),
    ],
    'retry' => [
        'times' => (int) env('OPENCODE_RETRY_TIMES', 3),
        'sleep' => (int) env('OPENCODE_RETRY_SLEEP', 100),
    ],
];
```

## Basic Usage

### Creating the Connector

```php
use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;

// Create a new connector instance
$opencode = new OpenCode();
```

### Listing Sessions

```php
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionList;

// Get all sessions
$response = $opencode->send(new SessionList());
$sessions = $response->json();
```

### Creating a Session

```php
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionCreate;

// Create a new session
$response = $opencode->send(new SessionCreate(
    title: 'My New Session',
    model: 'claude-3-5-sonnet-20241022'
));

$session = $response->json();
$sessionId = $session['id'];
```

### Sending a Prompt

```php
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionPrompt;

// Send a prompt to a session
$response = $opencode->send(new SessionPrompt(
    id: $sessionId,
    prompt: 'Write a hello world function in PHP'
));

$result = $response->json();
```

### Getting Messages

```php
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionMessages;

// Get all messages from a session
$response = $opencode->send(new SessionMessages(id: $sessionId));
$messages = $response->json();
```

### Getting Session Details

```php
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionGet;

// Get a specific session
$response = $opencode->send(new SessionGet(id: $sessionId));
$session = $response->json();
```

### Deleting a Session

```php
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionDelete;

// Delete a session
$response = $opencode->send(new SessionDelete(id: $sessionId));
```

## Advanced Usage

### Using Resources

The SDK organizes requests into resources for cleaner syntax:

```php
$opencode = new OpenCode();

// Access the misc resource
$misc = $opencode->misc();

// All requests are available through the resource
$sessions = $misc->sessionList();
```

### Custom Base URL

```php
use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;

$opencode = new OpenCode();
$opencode->baseUrl('https://custom-opencode-server.com');
```

### Custom Timeout

```php
$opencode = new OpenCode();
$opencode->config()->setTimeout(60); // 60 seconds
```

### Authentication

If your OpenCode API requires authentication:

```php
use Saloon\Http\Auth\TokenAuthenticator;

$opencode = new OpenCode();
$opencode->authenticate(new TokenAuthenticator('your-api-token'));
```

### Retry Logic

Saloon provides built-in retry capabilities. Configure retries:

```php
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

$opencode = new OpenCode();

// Retry on specific HTTP status codes
$opencode->config()->retry(
    times: 3,
    interval: 100, // milliseconds
    useExponentialBackoff: true
);
```

### Working with File Operations

```php
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\FileList;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\FileRead;

// List files
$response = $opencode->send(new FileList(path: '/src'));
$files = $response->json();

// Read a file
$response = $opencode->send(new FileRead(path: '/src/index.php'));
$content = $response->json();
```

### Managing Configuration

```php
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\ConfigGet;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\ConfigUpdate;

// Get current config
$response = $opencode->send(new ConfigGet());
$config = $response->json();

// Update config
$response = $opencode->send(new ConfigUpdate(
    // Pass config options as needed
));
```

## Testing

The SDK is fully testable using Saloon's testing utilities:

```php
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionList;

$mockClient = new MockClient([
    SessionList::class => MockResponse::make([
        ['id' => 'ses_123', 'title' => 'Test Session'],
    ], 200),
]);

$opencode = new OpenCode();
$opencode->withMockClient($mockClient);

$response = $opencode->send(new SessionList());
// Will return the mocked response
```

## Available Endpoints

The SDK provides access to all OpenCode API endpoints:

### Sessions
- `SessionList` - List all sessions
- `SessionCreate` - Create a new session
- `SessionGet` - Get session details
- `SessionUpdate` - Update session
- `SessionDelete` - Delete session
- `SessionFork` - Fork a session
- `SessionInit` - Initialize session
- `SessionAbort` - Abort session
- `SessionShare` - Share session
- `SessionUnshare` - Unshare session
- `SessionChildren` - Get child sessions
- `SessionTodo` - Get session todos
- `SessionDiff` - Get session diff
- `SessionSummarize` - Summarize session
- `SessionRevert` - Revert session
- `SessionUnrevert` - Unrevert session

### Messages
- `SessionMessages` - Get session messages
- `SessionPrompt` - Send a prompt
- `SessionMessage` - Get specific message
- `SessionCommand` - Execute command
- `SessionShell` - Execute shell command

### Files
- `FileList` - List files
- `FileRead` - Read file content
- `FileStatus` - Get file status

### Configuration
- `ConfigGet` - Get configuration
- `ConfigUpdate` - Update configuration
- `ConfigProviders` - Get providers

### Projects
- `ProjectList` - List projects
- `ProjectCurrent` - Get current project

### Tools
- `ToolIds` - Get tool IDs
- `ToolList` - List tools

### Search
- `FindText` - Search text
- `FindFiles` - Find files
- `FindSymbols` - Find symbols

### Other
- `PathGet` - Get path info
- `CommandList` - List commands
- `AppLog` - Get application logs
- `AppAgents` - Get agents
- `McpStatus` - Get MCP status
- `AuthSet` - Set authentication
- `EventSubscribe` - Subscribe to events

## Troubleshooting

### Connection Refused

If you receive connection errors, ensure OpenCode is running:

```bash
# Check if OpenCode is running on the expected port
curl http://localhost:3333
```

Update the base URL if using a different port:

```env
OPENCODE_BASE_URL=http://localhost:8080
```

### Timeout Errors

Increase the timeout for long-running requests:

```env
OPENCODE_TIMEOUT=120
```

Or programmatically:

```php
$opencode->config()->setTimeout(120);
```

### Authentication Errors

Ensure your authentication credentials are correct:

```env
OPENCODE_AUTH_TYPE=bearer
OPENCODE_AUTH_TOKEN=your-valid-token
```

### Class Not Found

If you encounter "class not found" errors, ensure autoloader is updated:

```bash
composer dump-autoload
```

## Memberware

This package is part of our internal toolkit and is optimized for our own purposes. We do not accept issues or PRs
in this repository. 

