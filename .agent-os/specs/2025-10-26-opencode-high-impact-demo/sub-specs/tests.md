# Tests Specification

This is the tests coverage details for the spec detailed in @.agent-os/specs/2025-10-26-opencode-high-impact-demo/spec.md

> Created: 2025-10-26
> Version: 1.0.0

## Test Coverage

### Unit Tests

**OpencodeService**
- Can create OpenCode client instance
- Can create session and return session ID
- Can list sessions and return array
- Can send prompt and return response
- Can fork session and return new session ID
- Can fetch session messages
- Can list directory contents
- Can read file contents
- Can search for text/files/symbols
- Handles API errors gracefully
- Returns proper error messages on failure

### Feature Tests

#### Enhanced Chat Interface Tests

**Session Management**
- Can render chat component
- Can create new session
- Can list all sessions in sidebar
- Can switch between sessions
- Can delete a session
- Can fork session at specific message
- Can load session children (forked sessions)
- Can rename session inline
- Session list updates after create/delete

**Message Handling**
- Can send message to session
- Can load message history
- Can display user and assistant messages
- Can parse and display message parts correctly
- Messages show with correct timestamps
- Empty messages are prevented from sending

**Session Actions**
- Can abort active session
- Can summarize session
- Can share session and get link
- Can unshare session
- Share button shows correct state

**Message Operations**
- Can view diff for message
- Diff displays code changes correctly
- Can revert message
- Can unrevert message
- Reverted messages show visual indicator

**Todos**
- Can load session todos
- Todos display as checklist
- Can toggle todo completion
- Todo count updates correctly

**Shell Commands**
- Can execute shell command
- Shell output displays in chat
- Shell errors display correctly
- Shell commands require confirmation (if enabled)

**Error Handling**
- Displays error when server unavailable
- Displays error on invalid session ID
- Displays error on API failure
- Shows connection status indicator
- Retry button works after error

#### Project Explorer Tests

**File Navigation**
- Can render explorer component
- Can list root directory contents
- Can expand directory to show contents
- Can navigate into subdirectory
- Can navigate using breadcrumbs
- Breadcrumbs show correct path
- Can toggle directory expand/collapse state

**File Viewing**
- Can open file and view contents
- File contents display with syntax highlighting
- Line numbers display correctly
- Large files load without freezing UI
- Binary files show appropriate message

**Project Management**
- Can list available projects
- Can switch between projects
- Current project displays correctly
- File tree updates after project switch

**Search Functionality**
- Can search for text across files
- Text search returns correct results
- Results show file path and line preview
- Can click result to open file
- Can search for files by name
- File search supports fuzzy matching
- Can search for symbols
- Symbol search returns functions/classes/etc
- Can clear search results
- Empty search shows appropriate message

**Performance**
- Large directory listings render quickly
- File content loads within 2 seconds
- Search results appear within 5 seconds
- UI remains responsive during operations

#### TUI Remote Control Tests

**Connection**
- Can render remote component
- Can connect to TUI server
- Connection status displays correctly
- Displays error when TUI not running

**Prompt Management**
- Can submit prompt to TUI
- Can append text to TUI prompt
- Can clear TUI prompt
- Prompt operations show loading state

**Actions**
- Can show toast in TUI
- Toast message displays in terminal
- Can execute TUI command
- Can open themes dialog in TUI
- Can open models dialog in TUI
- Can open help dialog in TUI
- Can open sessions dialog in TUI
- All actions show success/failure feedback

**Error Handling**
- Displays error when operation fails
- Shows connection lost message
- Retry button reconnects

#### Dashboard Tests

**Component Display**
- Can render dashboard
- All component cards display
- Component descriptions show correctly
- Endpoint counts are accurate
- Links to each component work

**Statistics**
- Coverage percentage calculates correctly
- Progress bar displays accurately
- Total endpoint count is correct

### Integration Tests

**End-to-End Chat Flow**
- User can create session, send messages, fork session, and switch between forks
- Forked sessions maintain conversation history correctly
- Message diffs show changes accurately
- Session tree visualization updates correctly

**End-to-End Explorer Flow**
- User can browse directories, open files, search for text, and open search results
- Search maintains state when switching between search types
- File viewer displays correct content for multiple file types

**End-to-End TUI Flow**
- User can connect to TUI, submit prompt, show toast, and execute command
- All TUI operations trigger expected terminal behavior
- Status updates reflect TUI state changes

### Browser Tests (Manual/Automated with Dusk)

**Visual Regression**
- Chat interface matches design mockups
- Explorer interface matches design mockups
- Remote interface matches design mockups
- Dashboard matches design mockups

**Responsive Behavior**
- Layout adjusts for projector resolution (1920x1080)
- Components readable from conference room distance
- No horizontal scrolling on standard screens

**Cross-Browser**
- Works in Chrome 120+
- Works in Firefox 120+
- Works in Safari 17+

### Mocking Requirements

**OpenCode API Mocking**
- Mock all OpenCode SDK responses for tests
- Use factories for consistent test data
- Mock session creation responses
- Mock message responses with realistic data
- Mock file listings and contents
- Mock search results
- Mock TUI operation responses

**Example Mock Data:**
```php
// Mock session response
[
    'id' => 'ses_test123',
    'created_at' => now()->toISOString(),
    'messages' => [],
]

// Mock message response
[
    'id' => 'msg_test456',
    'role' => 'assistant',
    'parts' => [
        [
            'type' => 'text',
            'text' => 'Test response',
        ],
    ],
]

// Mock file list response
[
    ['name' => 'src', 'type' => 'directory'],
    ['name' => 'tests', 'type' => 'directory'],
    ['name' => 'README.md', 'type' => 'file'],
]
```

### Performance Tests

**Load Testing**
- Component renders within 2 seconds
- Session list with 50 sessions loads quickly
- File tree with 1000 files navigable
- Search through 10,000 files completes in reasonable time

**Memory Testing**
- No memory leaks on repeated operations
- Message history doesn't grow unbounded
- File content cache releases properly

### Test Organization

```
packages/opencode-client/tests/
├── Unit/
│   ├── Services/
│   │   └── OpencodeServiceTest.php
│   └── Helpers/
│       └── SessionTreeBuilderTest.php
├── Feature/
│   ├── OpencodeChat/
│   │   ├── SessionManagementTest.php
│   │   ├── MessageHandlingTest.php
│   │   ├── SessionActionsTest.php
│   │   └── ErrorHandlingTest.php
│   ├── OpencodeExplorer/
│   │   ├── FileNavigationTest.php
│   │   ├── FileViewingTest.php
│   │   └── SearchFunctionalityTest.php
│   ├── OpencodeRemote/
│   │   ├── ConnectionTest.php
│   │   ├── PromptManagementTest.php
│   │   └── ActionsTest.php
│   └── OpencodeDashboard/
│       └── DashboardTest.php
└── Browser/
    ├── ChatFlowTest.php
    ├── ExplorerFlowTest.php
    └── RemoteFlowTest.php
```

### Continuous Integration

**Pre-commit Checks**
- All tests must pass
- No PHPStan errors
- Code formatted with Pint
- No obvious security issues

**CI Pipeline**
- Run full test suite on PR
- Run browser tests nightly
- Generate coverage report (target: 80%+)

### Test Data Management

**Fixtures**
- Sample project structure in `tests/fixtures/sample-project/`
- Sample OpenCode API responses in `tests/fixtures/api-responses/`
- Sample session data for tree visualization tests

**Database**
- Use in-memory SQLite for tests
- No database persistence needed (all in-memory)
- Migrations not required for demo

### Coverage Goals

- **Unit Tests**: 90%+ coverage of OpencodeService
- **Feature Tests**: 85%+ coverage of Livewire components
- **Integration Tests**: All critical user flows covered
- **Browser Tests**: All major user interactions verified

### Test Execution

```bash
# Run all tests
composer test

# Run specific test suite
php artisan test packages/opencode-client/tests/Feature/OpencodeChat

# Run with coverage
composer coverage

# Run browser tests
php artisan dusk packages/opencode-client/tests/Browser
```
