# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-10-26-opencode-chat-interface/spec.md

> Created: 2025-10-26
> Version: 1.0.0

## Technical Requirements

### Livewire Component Structure

- **Component:** `App\Livewire\OpencodeChat` (or within the package: `ArtisanBuild\OpencodeClient\Livewire\OpencodeChat`)
- **Route:** Register a route in the package service provider
- **Layout:** Full-page layout using Flux UI Pro components
- **State Management:** Component properties for server URL, session ID, messages array, loading states

### Component Properties

```php
public string $serverUrl = 'http://localhost:3333';
public ?string $authToken = null;
public ?string $sessionId = null;
public string $messageInput = '';
public array $messages = [];
public bool $connecting = false;
public bool $sending = false;
public ?string $error = null;
```

### UI Layout

- **Header Section:** Input field for OpenCode server URL and optional auth token
- **Connection Button:** "Connect" button to establish connection and create session
- **Chat Area:** Scrollable message list displaying user prompts and AI responses
- **Message Input:** Text area or input field with "Send" button for submitting prompts
- **Loading States:** Visual indicators when connecting or waiting for responses

### OpenCode SDK Integration

- Use `ArtisanBuild\OpencodeSdk\OpenCode\OpenCode` connector
- Configure base URL dynamically from user input
- Create session on connection using `SessionCreate` request
- Send prompts using `SessionPrompt` request
- Store session ID for subsequent requests

### Error Handling

- Catch and display connection errors (e.g., server unreachable)
- Display API errors from OpenCode responses
- Clear error messages when retrying or sending new messages
- Validate server URL format before attempting connection

### Message Display Format

- **User Messages:** Right-aligned, distinct styling
- **AI Messages:** Left-aligned, different background color
- **Timestamps:** Optional display of message time
- **Markdown Support:** Consider rendering AI responses with markdown (optional enhancement)

## Approach

**Selected Approach: Livewire Component within Package**

Create a self-contained Livewire component inside the `opencode-client` package that can be used by including the package and visiting a registered route.

**Rationale:**
- Keeps the chat interface portable and reusable across projects
- Follows the monorepo package pattern established in Kibble
- Easy to test and develop within the monorepo context
- Can be used as a standalone package when split

**Implementation Steps:**
1. Create Livewire component class in package namespace
2. Create Blade view using Flux UI components
3. Register route in package service provider
4. Add OpenCode SDK as a dependency in package composer.json
5. Implement connection and messaging logic
6. Add proper error handling and loading states

## External Dependencies

### OpenCode SDK
- **Package:** `artisan-build/opencode-sdk`
- **Justification:** Already exists in the monorepo and provides all necessary API integration
- **Installation:** Already available via path repository

### Flux UI Pro
- **Package:** `livewire/flux-pro`
- **Justification:** Consistent with project standards, provides chat-friendly components
- **Installation:** Already available in monorepo

### No Additional Dependencies Required
All necessary dependencies (Livewire, Flux UI, OpenCode SDK) are already available in the Kibble monorepo.

## Component Workflow

### Connection Flow
1. User enters server URL
2. User clicks "Connect"
3. Component validates URL format
4. Component creates OpenCode connector with custom base URL
5. Component sends `SessionCreate` request
6. On success, store session ID and enable message input
7. On failure, display error message

### Messaging Flow
1. User types message in input field
2. User clicks "Send" or presses Enter
3. Component adds user message to messages array
4. Component sets loading state
5. Component sends `SessionPrompt` request with session ID
6. On response, add AI message to messages array
7. Clear loading state and message input
8. Scroll to bottom of chat

### State Management
- All state maintained in Livewire component properties
- No database persistence for this proof of concept
- Session resets on page reload
- Component wire:model for reactive inputs
