# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-10-26-opencode-high-impact-demo/spec.md

> Created: 2025-10-26
> Status: Ready for Implementation

## Tasks

- [x] 1. Foundation & Shared Infrastructure
  - [x] 1.1 Write tests for OpencodeService high-impact methods
  - [x] 1.2 Extend OpencodeService with session management methods
  - [x] 1.3 Add OpencodeService methods for file operations
  - [x] 1.4 Add OpencodeService methods for TUI operations
  - [x] 1.5 Create shared Livewire component traits for error handling
  - [x] 1.6 Install highlight.js for syntax highlighting
  - [x] 1.7 Install vis-network for session tree visualization
  - [x] 1.8 Verify all tests pass

- [x] 2. Enhanced Chat Interface - Core Session Management
  - [x] 2.1 Write tests for OpencodeChat session list functionality
  - [x] 2.2 Implement session list sidebar with create/delete
  - [x] 2.3 Add session switcher functionality
  - [x] 2.4 Implement session update/rename capability
  - [x] 2.5 Add visual indicators for active session
  - [x] 2.6 Create Blade view for session sidebar
  - [x] 2.7 Verify all tests pass

- [x] 3. Enhanced Chat Interface - Message Management
  - [x] 3.1 Write tests for message history loading
  - [x] 3.2 Implement SessionMessages endpoint integration
  - [x] 3.3 Add message bubbles with proper styling (user vs assistant)
  - [x] 3.4 Display message metadata (timestamp, role)
  - [x] 3.5 Add message actions on hover (fork, diff, revert)
  - [x] 3.6 Create Blade components for message display
  - [x] 3.7 Verify all tests pass

- [x] 4. Enhanced Chat Interface - Session Forking
  - [x] 4.1 Write tests for session forking functionality
  - [x] 4.2 Implement SessionFork endpoint integration
  - [x] 4.3 Add fork button to messages
  - [x] 4.4 Implement SessionChildren to load forked sessions
  - [x] 4.5 Display session hierarchy in sidebar
  - [x] 4.6 Add visual indicators for parent/child relationships
  - [x] 4.7 Verify all tests pass

- [x] 5. Enhanced Chat Interface - Session Tree Visualization
  - [x] 5.1 Write tests for tree visualization modal
  - [x] 5.2 Create modal component for tree display
  - [x] 5.3 Integrate vis-network library
  - [x] 5.4 Build session tree data structure from SessionChildren
  - [x] 5.5 Render interactive tree with clickable nodes
  - [x] 5.6 Add navigation to sessions from tree
  - [x] 5.7 Verify all tests pass

- [x] 6. Enhanced Chat Interface - Message Diffs
  - [x] 6.1 Write tests for diff viewing functionality
  - [x] 6.2 Implement SessionDiff endpoint integration
  - [x] 6.3 Create diff viewer modal component
  - [x] 6.4 Add syntax highlighting to diff display
  - [x] 6.5 Show additions/deletions with color coding
  - [x] 6.6 Add "View Diff" button to relevant messages
  - [x] 6.7 Verify all tests pass

- [x] 7. Enhanced Chat Interface - Message Revert
  - [x] 7.1 Write tests for revert/unrevert functionality
  - [x] 7.2 Implement SessionRevert endpoint integration
  - [x] 7.3 Implement SessionUnrevert endpoint integration
  - [x] 7.4 Add revert button to messages
  - [x] 7.5 Show reverted state with visual indicators
  - [x] 7.6 Add unrevert button to reverted messages
  - [x] 7.7 Verify all tests pass

- [x] 8. Enhanced Chat Interface - Session Actions
  - [x] 8.1 Write tests for session actions dropdown
  - [x] 8.2 Implement SessionAbort endpoint integration
  - [x] 8.3 Implement SessionSummarize endpoint integration
  - [x] 8.4 Implement SessionShare/SessionUnshare endpoints
  - [x] 8.5 Create actions dropdown in header
  - [x] 8.6 Add share link copy functionality
  - [x] 8.7 Verify all tests pass

- [x] 9. Enhanced Chat Interface - Todos
  - [x] 9.1 Write tests for todo list functionality
  - [x] 9.2 Implement SessionTodo endpoint integration
  - [x] 9.3 Create sliding panel for todos
  - [x] 9.4 Display todos as checkboxes
  - [x] 9.5 Add toggle functionality for todo completion
  - [x] 9.6 Update todo count in UI
  - [x] 9.7 Verify all tests pass

- [ ] 10. Enhanced Chat Interface - Shell Commands
  - [ ] 10.1 Write tests for shell command execution
  - [ ] 10.2 Implement SessionShell endpoint integration
  - [ ] 10.3 Add shell command input field
  - [ ] 10.4 Display shell output in chat
  - [ ] 10.5 Add confirmation modal for dangerous commands
  - [ ] 10.6 Show loading state during execution
  - [ ] 10.7 Verify all tests pass

- [ ] 11. Enhanced Chat Interface - Permissions
  - [ ] 11.1 Write tests for permission handling
  - [ ] 11.2 Implement PostSessionIdPermissionsPermissionId endpoint
  - [ ] 11.3 Create permission request modal
  - [ ] 11.4 Add approve/deny buttons
  - [ ] 11.5 Handle permission response
  - [ ] 11.6 Update UI based on permission state
  - [ ] 11.7 Verify all tests pass

- [ ] 12. Project Explorer - Core File Browser
  - [ ] 12.1 Write tests for OpencodeExplorer component
  - [ ] 12.2 Create OpencodeExplorer Livewire component
  - [ ] 12.3 Implement FileList endpoint integration
  - [ ] 12.4 Build file tree structure from API response
  - [ ] 12.5 Add expand/collapse functionality for directories
  - [ ] 12.6 Style file tree with indentation and icons
  - [ ] 12.7 Verify all tests pass

- [ ] 13. Project Explorer - File Viewing
  - [ ] 13.1 Write tests for file content viewing
  - [ ] 13.2 Implement FileRead endpoint integration
  - [ ] 13.3 Create file content viewer component
  - [ ] 13.4 Add syntax highlighting with highlight.js
  - [ ] 13.5 Display line numbers
  - [ ] 13.6 Add breadcrumb navigation
  - [ ] 13.7 Verify all tests pass

- [ ] 14. Project Explorer - File Status
  - [ ] 14.1 Write tests for git status indicators
  - [ ] 14.2 Implement FileStatus endpoint integration
  - [ ] 14.3 Add visual badges for modified files
  - [ ] 14.4 Add visual badges for added files
  - [ ] 14.5 Add visual badges for deleted files
  - [ ] 14.6 Update file tree with status indicators
  - [ ] 14.7 Verify all tests pass

- [ ] 15. Project Explorer - Search Functionality
  - [ ] 15.1 Write tests for text search
  - [ ] 15.2 Implement FindText endpoint integration
  - [ ] 15.3 Implement FindFiles endpoint integration
  - [ ] 15.4 Implement FindSymbols endpoint integration
  - [ ] 15.5 Create search interface with tab switching
  - [ ] 15.6 Display search results with previews
  - [ ] 15.7 Add click-to-open functionality from results
  - [ ] 15.8 Verify all tests pass

- [ ] 16. Project Explorer - Project Management
  - [ ] 16.1 Write tests for project switching
  - [ ] 16.2 Implement ProjectList endpoint integration
  - [ ] 16.3 Implement ProjectCurrent endpoint integration
  - [ ] 16.4 Create project switcher dropdown
  - [ ] 16.5 Refresh file tree on project switch
  - [ ] 16.6 Update breadcrumbs for new project
  - [ ] 16.7 Verify all tests pass

- [ ] 17. TUI Remote Control - Core Setup
  - [ ] 17.1 Write tests for OpencodeRemote component
  - [ ] 17.2 Create OpencodeRemote Livewire component
  - [ ] 17.3 Add connection status check
  - [ ] 17.4 Create main layout with sections
  - [ ] 17.5 Style component for projector visibility
  - [ ] 17.6 Add error handling for TUI not running
  - [ ] 17.7 Verify all tests pass

- [ ] 18. TUI Remote Control - Prompt Management
  - [ ] 18.1 Write tests for prompt operations
  - [ ] 18.2 Implement TuiSubmitPrompt endpoint integration
  - [ ] 18.3 Implement TuiAppendPrompt endpoint integration
  - [ ] 18.4 Implement TuiClearPrompt endpoint integration
  - [ ] 18.5 Create prompt input section with buttons
  - [ ] 18.6 Add loading states for operations
  - [ ] 18.7 Verify all tests pass

- [ ] 19. TUI Remote Control - Quick Actions
  - [ ] 19.1 Write tests for quick action buttons
  - [ ] 19.2 Implement TuiShowToast endpoint integration
  - [ ] 19.3 Implement TuiOpenThemes endpoint integration
  - [ ] 19.4 Implement TuiOpenModels endpoint integration
  - [ ] 19.5 Implement TuiOpenHelp endpoint integration
  - [ ] 19.6 Implement TuiOpenSessions endpoint integration
  - [ ] 19.7 Create quick actions button grid
  - [ ] 19.8 Add success feedback for each action
  - [ ] 19.9 Verify all tests pass

- [ ] 20. TUI Remote Control - Command Execution
  - [ ] 20.1 Write tests for command execution
  - [ ] 20.2 Implement TuiExecuteCommand endpoint integration
  - [ ] 20.3 Create command dropdown with available commands
  - [ ] 20.4 Add execute button with loading state
  - [ ] 20.5 Display execution results
  - [ ] 20.6 Handle command errors gracefully
  - [ ] 20.7 Verify all tests pass

- [ ] 21. Dashboard Landing Page
  - [ ] 21.1 Write tests for OpencodeDashboard component
  - [ ] 21.2 Create OpencodeDashboard Livewire component
  - [ ] 21.3 Build component cards for each demo feature
  - [ ] 21.4 Add coverage progress bar (38/51 endpoints)
  - [ ] 21.5 Create navigation links to each component
  - [ ] 21.6 Style dashboard for conference presentation
  - [ ] 21.7 Add description/about section
  - [ ] 21.8 Verify all tests pass

- [ ] 22. Routes & Navigation
  - [ ] 22.1 Write tests for route definitions
  - [ ] 22.2 Add route for /opencode (dashboard)
  - [ ] 22.3 Add route for /opencode-chat
  - [ ] 22.4 Add route for /opencode-explorer
  - [ ] 22.5 Add route for /opencode-remote
  - [ ] 22.6 Add navigation component with links
  - [ ] 22.7 Verify all routes work correctly

- [ ] 23. Styling & Polish
  - [ ] 23.1 Apply Flux UI Pro components consistently
  - [ ] 23.2 Implement projector-safe color palette
  - [ ] 23.3 Set typography to conference-optimized sizes
  - [ ] 23.4 Add animations and transitions
  - [ ] 23.5 Ensure responsive behavior for 1920x1080
  - [ ] 23.6 Test visibility from conference room distance
  - [ ] 23.7 Verify all tests pass

- [ ] 24. Integration Testing & Demo Preparation
  - [ ] 24.1 Write end-to-end browser tests for chat flow
  - [ ] 24.2 Write end-to-end browser tests for explorer flow
  - [ ] 24.3 Write end-to-end browser tests for TUI flow
  - [ ] 24.4 Create sample session data for demos
  - [ ] 24.5 Test all flows with OpenCode server running
  - [ ] 24.6 Create demo script for conference presentation
  - [ ] 24.7 Record backup video of working demo
  - [ ] 24.8 Verify all tests pass
