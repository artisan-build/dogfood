# OpenCode SDK Demo - Quick Reference

## 7 Demo Components = 51 Endpoints Covered

### 🎯 Component Coverage Map

```
┌─────────────────────────────┬──────────────┬───────────────────────────┐
│ Component                   │ Endpoints    │ Primary Demo Value        │
├─────────────────────────────┼──────────────┼───────────────────────────┤
│ 1. Enhanced Chat            │ 20/51 (39%) │ Core workflow            │
│    /opencode-chat           │              │ Session branching        │
│                             │              │ Message history          │
├─────────────────────────────┼──────────────┼───────────────────────────┤
│ 2. Project Explorer         │ 9/51  (18%) │ IDE-like features        │
│    /opencode-explorer       │              │ Code search              │
│                             │              │ File navigation          │
├─────────────────────────────┼──────────────┼───────────────────────────┤
│ 3. Agent Dashboard          │ 4/51  (8%)  │ Extensibility            │
│    /opencode-agents         │              │ Tool ecosystem           │
├─────────────────────────────┼──────────────┼───────────────────────────┤
│ 4. Configuration Manager    │ 4/51  (8%)  │ Multi-provider setup     │
│    /opencode-config         │              │ Credential management    │
├─────────────────────────────┼──────────────┼───────────────────────────┤
│ 5. Events Monitor           │ 3/51  (6%)  │ Debugging & observability│
│    /opencode-monitor        │              │ Real-time event stream   │
├─────────────────────────────┼──────────────┼───────────────────────────┤
│ 6. TUI Remote Control       │ 9/51  (18%) │ Unique feature!          │
│    /opencode-remote         │              │ Control terminal from web│
├─────────────────────────────┼──────────────┼───────────────────────────┤
│ 7. Init Wizard              │ 1/51  (2%)  │ Onboarding experience    │
│    /opencode-init           │              │ Project setup            │
└─────────────────────────────┴──────────────┴───────────────────────────┘

Missing: 1 endpoint (SessionShell) - included in Chat component
```

## 📊 Endpoint Categories

| Category                  | Count | Status      |
|--------------------------|-------|-------------|
| Session Management       | 13    | Chat        |
| Session Messages         | 7     | Chat        |
| Session Shell/Perms      | 2     | Chat        |
| File Operations          | 3     | Explorer    |
| Code Search              | 3     | Explorer    |
| Project Management       | 3     | Explorer    |
| TUI Operations           | 9     | Remote      |
| Configuration            | 3     | Config      |
| Tools & Agents           | 3     | Agents      |
| Commands                 | 1     | Agents      |
| Events & Logging         | 2     | Monitor     |
| MCP                      | 1     | Monitor     |
| Authentication           | 1     | Config      |
| **Total**                | **51**| **All**     |

## 🚀 Implementation Priorities

### Phase 1: Enhanced Chat (MUST HAVE)
**Time: ~1 week | Impact: Very High**

Features that make the best demo:
- ✅ Basic chat (already done!)
- [ ] Session switcher with list
- [ ] Session branching/forking
- [ ] Message diffs
- [ ] Todo list integration
- [ ] Session actions (abort, summarize)

**Why first**: Core functionality everyone understands

### Phase 2: Project Explorer (HIGH VALUE)
**Time: ~1 week | Impact: High**

Features that show IDE-like power:
- [ ] File tree browser
- [ ] Code viewer with highlighting
- [ ] Search (text, files, symbols)
- [ ] Project switcher

**Why second**: Demonstrates code intelligence

### Phase 3: TUI Remote Control (WOW FACTOR)
**Time: ~3 days | Impact: Medium but unique**

Features that stand out:
- [ ] Send commands to TUI
- [ ] Remote notifications
- [ ] Dialog triggers
- [ ] Live preview of TUI state

**Why third**: Most unique feature, conference "wow" moment

### Phase 4: Config & Agents (NICE TO HAVE)
**Time: ~1 week | Impact: Medium**

Features that show flexibility:
- [ ] Provider management
- [ ] Agent discovery
- [ ] Config editing
- [ ] Tool registry

**Why fourth**: Important for adoption but less visually impressive

### Phase 5: Monitor & Init (OPTIONAL)
**Time: ~1 week | Impact: Low**

Features for completeness:
- [ ] Event stream viewer
- [ ] MCP status
- [ ] Init wizard

**Why last**: More for developers integrating SDK than demo

## 🎬 20-Minute Conference Demo Script

### Minute 0-2: Introduction
- "Today I'll show you OpenCode SDK - 51 endpoints for AI coding agents"
- "Built this demo app to showcase every feature"
- "Let's explore how to integrate AI into your dev tools"

### Minute 2-7: Enhanced Chat (Act 1)
- Create session: "What's the best way to handle auth in Laravel?"
- Show response with code
- Fork session: "Actually, let's explore JWT tokens instead"
- Show session tree with branches
- Revert a message, show diff visualization
- Display session todos

**Key Message**: "Natural branching conversations, not linear chat"

### Minute 7-11: Project Explorer (Act 2)
- Switch to explorer view
- Browse real project files
- Search for "middleware" across codebase
- Find symbol "User::class"
- Open file, show syntax highlighting
- Navigate file tree

**Key Message**: "IDE-level code intelligence via API"

### Minute 11-14: TUI Remote (Act 3)
- Open remote control
- Show OpenCode TUI running in terminal (projected)
- Send command from web to TUI
- Show toast appearing in terminal
- Open theme picker remotely
- Execute agent cycle command

**Key Message**: "Control your terminal AI from any interface"

### Minute 14-17: Config & Agents (Act 4)
- Show provider dashboard (Anthropic, OpenAI, local models)
- Switch provider mid-session
- Browse agent catalog
- Show tool JSON schemas
- Demonstrate custom command

**Key Message**: "Works with any LLM, extensible agent system"

### Minute 17-19: Live Coding (Act 5)
- Return to chat
- "Let's implement a feature together"
- Ask: "Add a rate limiter to our API"
- Show file changes in explorer in real-time
- Run tests via shell command
- Show success

**Key Message**: "Full development workflow in one SDK"

### Minute 19-20: Call to Action
- Show QR code for demo URL
- GitHub repo link
- "51 endpoints, infinite possibilities"
- "Start building today"

## 🎨 Visual Design Recommendations

### Color Scheme (Projector-Safe)
```
Primary: #3B82F6 (Blue)
Success: #10B981 (Green)
Warning: #F59E0B (Amber)
Error: #EF4444 (Red)
Background Light: #F9FAFB
Background Dark: #111827
Text: #1F2937
```

### Typography
- Headings: Inter (bold, 24-32px)
- Body: Inter (regular, 16-18px)
- Code: JetBrains Mono (14-16px)

### Layout
- Max width: 1400px (readable on 1080p projectors)
- Generous padding (conference rooms have poor viewing angles)
- High contrast (bad lighting in conference halls)
- Animations: Subtle, 200-300ms (not distracting)

## 🛠️ Tech Stack for Demo

### Frontend
- Livewire 3 (reactive components)
- Flux UI Pro (components)
- TailwindCSS 4 (styling)
- Alpine.js (interactions)

### Visualization
- Highlight.js (syntax highlighting)
- Monaco Editor (code diffs)
- D3.js or vis.js (session tree visualization)
- Chart.js (metrics/usage graphs)

### Real-time
- Livewire polling for events
- Server-sent events for monitoring
- WebSockets (if needed for TUI sync)

## 📝 Pre-Demo Checklist

### 1 Week Before
- [ ] All components built and tested
- [ ] Sample project repository prepared
- [ ] Pre-configured prompts/scenarios
- [ ] Video backup recorded
- [ ] Slide deck created

### 1 Day Before
- [ ] OpenCode server running on laptop
- [ ] Demo app deployed locally
- [ ] Browser tabs pre-opened
- [ ] Projector settings tested
- [ ] Font sizes readable from back row
- [ ] Backup laptop configured

### 1 Hour Before
- [ ] Test complete flow 2x
- [ ] Clear browser cache
- [ ] Close unnecessary apps
- [ ] Disable notifications
- [ ] Put phone in airplane mode
- [ ] Have water nearby

### During Talk
- [ ] Speak slowly (nervous = fast talking)
- [ ] Face audience, not screen
- [ ] Pause after each "wow" moment
- [ ] If something breaks, joke and move on
- [ ] End with QR code visible for 60 seconds

## 🐛 Demo Failure Contingencies

### If OpenCode server crashes
→ Switch to pre-recorded video
→ "Let me show you what it looks like when it works"
→ Continue with live explorer/config (doesn't need server)

### If internet fails (unlikely for local server)
→ Use offline mode
→ "Good thing we built this to work offline"
→ Highlight local-first architecture

### If projector has issues
→ Share screen to conference wifi
→ "Everyone can see better on their laptops anyway"
→ Send link via chat

### If laptop crashes
→ Switch to backup laptop
→ "While this reboots, let me tell you about..."
→ Keep talking, don't panic

## 💡 Unique Selling Points

1. **Complete Coverage**: 51 endpoints, not a subset
2. **Battle-Tested**: Built with real SDK in production Laravel app
3. **Extensible**: Show how to add custom endpoints
4. **Type-Safe**: PHP SDK with full typing
5. **Well-Documented**: Code examples for every endpoint
6. **Open Source**: MIT licensed, contribute freely
7. **Framework Agnostic**: Works in any PHP app
8. **Multi-Provider**: Anthropic, OpenAI, local models, etc.
9. **TUI Control**: Unique feature not available elsewhere
10. **Conference-Proven**: You're watching it work right now!

## 📚 Post-Conference Materials

### For Attendees
- [ ] Demo source code on GitHub
- [ ] Blog post with architecture deep-dive
- [ ] Video recording of presentation
- [ ] Starter kit with basic implementation
- [ ] Discord server invite

### For You
- [ ] Feedback form analysis
- [ ] Connections made (LinkedIn)
- [ ] Speaking opportunities identified
- [ ] Partnership inquiries
- [ ] Blog post on conference experience

---

**Remember**: The demo is not about showing off. It's about inspiring developers to build amazing AI-powered tools. Make them believe they can do it too.

Good luck! 🚀
