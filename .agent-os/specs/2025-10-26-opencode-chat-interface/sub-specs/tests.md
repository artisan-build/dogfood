# Tests Specification

This is the tests coverage details for the spec detailed in @.agent-os/specs/2025-10-26-opencode-chat-interface/spec.md

> Created: 2025-10-26
> Version: 1.0.0

## Test Coverage

### Unit Tests

**OpencodeChat Component**
- Component can be instantiated
- Component initializes with default server URL
- Component initializes with empty messages array
- Component validates server URL format
- Component handles empty message input validation

### Integration Tests

**Connection Functionality**
- User can connect to a mock OpenCode server
- Component creates a session on connection
- Component stores session ID after successful connection
- Component displays error message when connection fails
- Component shows loading state during connection

**Messaging Functionality**
- User can send a message to OpenCode
- Component adds user message to messages array
- Component sends prompt request with correct session ID
- Component adds AI response to messages array
- Component clears message input after sending
- Component shows loading state while waiting for response
- Component displays error when message sending fails

**UI Rendering**
- Component renders server URL input field
- Component renders connection button
- Component renders message input area
- Component renders send button
- Component displays messages in correct order
- Component shows loading indicator when connecting
- Component shows loading indicator when sending message

### Feature Tests

**End-to-End Chat Flow**
- User can complete full chat workflow: connect → send message → receive response
- Multiple messages can be sent in sequence
- Error states clear when retrying
- Component remains functional after error recovery

### Mocking Requirements

**OpenCode SDK Mocking**
- **OpenCode Connector:** Mock the connector to avoid actual API calls
- **SessionCreate Request:** Mock response with session ID
- **SessionPrompt Request:** Mock response with AI assistant message
- **Error Scenarios:** Mock connection failures and API errors

**Mocking Strategy:**
- Use Saloon's `MockClient` for mocking OpenCode SDK requests
- Create mock responses that match OpenCode API response format
- Test both success and failure scenarios

**Example Mock Setup:**
```php
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionCreate;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionPrompt;

$mockClient = new MockClient([
    SessionCreate::class => MockResponse::make([
        'id' => 'ses_test_123',
        'title' => 'Test Session',
    ], 200),
    SessionPrompt::class => MockResponse::make([
        'message' => 'Hello! How can I help you?',
    ], 200),
]);
```

## Test Organization

### Test File Location
`packages/opencode-client/tests/Feature/OpencodeChat/OpencodeChat<Feature>Test.php`

**Suggested Test Files:**
- `OpencodeConnectionTest.php` - Connection and session creation tests
- `OpencodeMessagingTest.php` - Message sending and receiving tests
- `OpencodeUITest.php` - Component rendering and UI state tests
- `OpencodeErrorHandlingTest.php` - Error scenarios and recovery tests

### Testing Tools
- **Pest PHP:** Use Pest for test syntax
- **Livewire Testing:** Use Livewire's test helpers (`Livewire::test()`)
- **Saloon Mocking:** Use Saloon's mock client for SDK requests

### Example Test Structure

```php
use Livewire\Livewire;
use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;

it('can connect to opencode server', function () {
    // Mock OpenCode SDK responses

    Livewire::test(OpencodeChat::class)
        ->set('serverUrl', 'http://localhost:3333')
        ->call('connect')
        ->assertSet('sessionId', 'ses_test_123')
        ->assertSet('connecting', false)
        ->assertSee('Connected');
});

it('can send a message', function () {
    // Mock OpenCode SDK responses

    Livewire::test(OpencodeChat::class)
        ->set('sessionId', 'ses_test_123')
        ->set('messageInput', 'Hello OpenCode')
        ->call('sendMessage')
        ->assertSet('messageInput', '')
        ->assertCount('messages', 2); // User + AI message
});
```

## Coverage Goals

- **Line Coverage:** Minimum 80%
- **Branch Coverage:** Minimum 75%
- **Focus Areas:** Connection logic, message handling, error scenarios
- **Edge Cases:** Empty inputs, network failures, malformed responses
