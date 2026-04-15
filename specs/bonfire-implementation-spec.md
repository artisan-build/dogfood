# Bonfire Implementation Spec

> **Package:** artisan-build/bonfire
> **Version:** 1.0.0
> **Created:** 2025-04-15
> **PRD Reference:** specs/bonfire-prd-v4.docx

## Overview

Bonfire is a Laravel package providing embedded, real-time group chat for Laravel applications. It is inspired by Campfire (37signals) with the addition of one-level-deep threaded replies. The package uses a polymorphic Member model to decouple chat identity from the host application's user system.

**Key Philosophy:** Minimal configuration, maximal functionality. Human users, bots, and AI agents participate as equals through the Member abstraction.

---

## Technical Requirements

### Host Application Requirements

| Requirement | Version | Notes |
|-------------|---------|-------|
| PHP | 8.2+ | Uses enums, readonly properties |
| Laravel | 11.x+ | First-class Reverb support required |
| Laravel Reverb | 1.x | WebSocket server for real-time |
| Livewire | 4.x | **Uses multi-file components (MFC)** |
| Flux UI Pro | Latest | **Hard dependency** - no custom CSS |
| Queue driver | Any | Redis recommended |
| Broadcast driver | Reverb | Other drivers untested |

### Package Dependencies (composer.json)

```json
{
    "require": {
        "php": "^8.2",
        "illuminate/support": "^11.36|^12.0",
        "livewire/livewire": "^4.0",
        "league/commonmark": "^2.0"
    }
}
```

**Note:** Flux UI Pro is resolved by the host app, not declared as a Composer dependency.

---

## Package Structure

```
artisan-build/bonfire/
├── config/
│   └── bonfire.php
├── database/
│   └── migrations/
│       ├── 2025_01_01_000001_create_bonfire_members_table.php
│       ├── 2025_01_01_000002_create_bonfire_rooms_table.php
│       ├── 2025_01_01_000003_create_bonfire_member_room_table.php
│       ├── 2025_01_01_000004_create_bonfire_messages_table.php
│       ├── 2025_01_01_000005_create_bonfire_reactions_table.php
│       ├── 2025_01_01_000006_create_bonfire_attachments_table.php
│       ├── 2025_01_01_000007_create_bonfire_link_previews_table.php
│       └── 2025_01_01_000008_create_bonfire_mentions_table.php
├── routes/
│   └── web.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── bonfire.blade.php
│       └── components/
│           ├── ⚡room-index/
│           │   ├── room-index.php
│           │   └── room-index.blade.php
│           ├── ⚡room-show/
│           │   ├── room-show.php
│           │   └── room-show.blade.php
│           ├── ⚡message-composer/
│           │   ├── message-composer.php
│           │   └── message-composer.blade.php
│           ├── ⚡message-list/
│           │   ├── message-list.php
│           │   └── message-list.blade.php
│           ├── ⚡thread-panel/
│           │   ├── thread-panel.php
│           │   └── thread-panel.blade.php
│           └── ⚡admin-panel/
│               ├── admin-panel.php
│               └── admin-panel.blade.php
├── src/
│   ├── Providers/
│   │   └── BonfireServiceProvider.php
│   ├── Facades/
│   │   └── Bonfire.php
│   ├── BonfireManager.php
│   ├── Enums/
│   │   ├── BonfireRole.php
│   │   └── RoomType.php
│   ├── Models/
│   │   ├── Member.php
│   │   ├── Room.php
│   │   ├── Message.php
│   │   ├── Reaction.php
│   │   ├── Attachment.php
│   │   ├── LinkPreview.php
│   │   └── Mention.php
│   ├── Events/
│   │   ├── MessagePosted.php
│   │   ├── MessageDeleted.php
│   │   ├── ReactionToggled.php
│   │   └── UserTyping.php
│   ├── Jobs/
│   │   └── FetchLinkPreview.php
│   ├── Notifications/
│   │   └── MentionedInMessage.php
│   ├── Http/
│   │   └── Controllers/
│   │       └── AttachmentController.php
│   ├── Observers/
│   │   └── BonfireMemberObserver.php
│   ├── Traits/
│   │   └── HasBonfireProfile.php
│   ├── Console/
│   │   └── Commands/
│   │       ├── InstallCommand.php
│   │       └── CreateRoomCommand.php
│   └── Support/
│       └── MarkdownRenderer.php
└── tests/
    ├── Pest.php
    ├── TestCase.php
    ├── Feature/
    │   ├── MemberRegistrationTest.php
    │   ├── RoomAccessTest.php
    │   ├── MessagePostingTest.php
    │   ├── ThreadsTest.php
    │   ├── ReactionsTest.php
    │   ├── MentionsTest.php
    │   ├── AttachmentsTest.php
    │   └── RoleEnforcementTest.php
    └── Unit/
        ├── RoomTypeEnumTest.php
        ├── MarkdownRendererTest.php
        └── MemberModelTest.php
```

---

## Livewire 4 Multi-File Components

**CRITICAL:** This package MUST use Livewire 4 multi-file components (MFC). This is a strict requirement.

### MFC Structure

Each Livewire component lives in a directory prefixed with the lightning bolt emoji (⚡):

```
resources/views/components/⚡room-show/
├── room-show.php          # PHP class file
├── room-show.blade.php    # Blade template
```

### MFC Class Example

```php
<?php
// resources/views/components/⚡room-show/room-show.php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Livewire;

use ArtisanBuild\Bonfire\Models\Room;
use Livewire\Component;

final class RoomShow extends Component
{
    public Room $room;

    public function mount(Room $room): void
    {
        $this->room = $room;
    }
}
```

### Component Registration

In the service provider, register the MFC view path:

```php
public function boot(): void
{
    Livewire::addComponentPath(
        __DIR__.'/../../resources/views/components'
    );
}
```

Components are then referenced as `bonfire::room-show` (or whatever naming convention you establish).

---

## Data Model

### members table

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigint unsigned | No | Auto-increment PK |
| tenant_id | bigint unsigned | Yes | Default null. Host app scopes as needed. |
| memberable_type | varchar(255) | No | Polymorphic: FQCN of host model |
| memberable_id | bigint unsigned | No | Polymorphic: PK of host model |
| display_name | varchar(255) | No | Denormalised from host model |
| avatar_url | varchar(2048) | Yes | Denormalised from host model |
| role | varchar(20) | No | member \| moderator \| admin. Default: member |
| is_active | boolean | No | Default true |
| created_at / updated_at | timestamps | No | |

**Unique constraint:** (memberable_type, memberable_id, tenant_id)

### rooms table

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigint unsigned | No | Auto-increment PK |
| tenant_id | bigint unsigned | Yes | Default null |
| name | varchar(255) | No | Display name |
| slug | varchar(255) | No | URL-safe. Unique per tenant. |
| description | text | Yes | |
| type | tinyint unsigned | No | Default 0. Bitmask cast to RoomType. |
| created_by | bigint unsigned | No | FK to members |
| created_at / updated_at | timestamps | No | |

### member_room pivot table

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| member_id | bigint unsigned | No | FK to members |
| room_id | bigint unsigned | No | FK to rooms |
| created_by | bigint unsigned | Yes | FK to members. Null if programmatic. |
| last_read_at | timestamp | Yes | For unread tracking in private rooms |
| created_at | timestamp | No | |

**Primary key:** composite (member_id, room_id)

### messages table

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigint unsigned | No | Auto-increment PK |
| tenant_id | bigint unsigned | Yes | Default null |
| room_id | bigint unsigned | No | FK to rooms |
| member_id | bigint unsigned | No | FK to members |
| parent_id | bigint unsigned | Yes | Self-ref. Null = root. Non-null = reply. |
| body | text | No | Raw Markdown content |
| deleted_at | timestamp | Yes | Soft delete |
| created_at / updated_at | timestamps | No | |

**Constraint:** Thread replies (parent_id not null) cannot have their own replies. Enforced at application layer.

### reactions table

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| message_id | bigint unsigned | No | FK to messages |
| member_id | bigint unsigned | No | FK to members |
| created_at | timestamp | No | |

**Unique constraint:** (message_id, member_id) - Toggle behavior: insert if absent, delete if present.

### attachments table

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigint unsigned | No | Auto-increment PK |
| message_id | bigint unsigned | No | FK to messages |
| disk | varchar(50) | No | Laravel disk name |
| path | varchar(500) | No | Path on disk |
| filename | varchar(255) | No | Original filename |
| mime_type | varchar(100) | No | e.g. image/jpeg |
| size | bigint unsigned | No | Bytes |
| created_at | timestamp | No | |

**Storage path pattern:** `bonfire/{tenant_id}/{room_id}/` where tenant_id is cast to `0` when null.

### link_previews table

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigint unsigned | No | Auto-increment PK |
| message_id | bigint unsigned | No | FK to messages. One preview per message. |
| url | varchar(2048) | No | The resolved URL |
| title | varchar(500) | Yes | og:title or `<title>` |
| description | text | Yes | og:description |
| image_url | varchar(2048) | Yes | og:image |
| site_name | varchar(255) | Yes | og:site_name |
| fetched_at | timestamp | No | When fetch completed |
| failed | boolean | No | Default false |

### mentions table

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| message_id | bigint unsigned | No | FK to messages |
| member_id | bigint unsigned | No | FK to members |
| created_at | timestamp | No | |

---

## Enums

### BonfireRole

```php
<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Enums;

enum BonfireRole: string
{
    case Member = 'member';
    case Moderator = 'moderator';
    case Admin = 'admin';

    /**
     * Check if this role has at least the given role's permissions.
     */
    public function hasAtLeast(self $role): bool
    {
        return match ($this) {
            self::Admin => true,
            self::Moderator => $role !== self::Admin,
            self::Member => $role === self::Member,
        };
    }
}
```

### RoomType (Bitmask Flags Enum)

```php
<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Enums;

/**
 * Bitmask flags for room types. Flags compose freely.
 *
 * Examples:
 * - type = 0: Public, active, unrestricted
 * - type = 1: Private only
 * - type = 5 (1|4): Private + Announcements
 * - type = 3 (1|2): Private + Archived
 */
enum RoomType: int
{
    case Private = 1;       // 2^0 - Restricted to member_room pivot members
    case Archived = 2;      // 2^1 - Read-only, no new messages
    case Announcements = 4; // 2^2 - Only moderators/admins may post

    /**
     * Check if the bitmask has this flag.
     */
    public static function has(int $bitmask, self $flag): bool
    {
        return ($bitmask & $flag->value) === $flag->value;
    }

    /**
     * Add this flag to a bitmask.
     */
    public static function add(int $bitmask, self $flag): int
    {
        return $bitmask | $flag->value;
    }

    /**
     * Remove this flag from a bitmask.
     */
    public static function remove(int $bitmask, self $flag): int
    {
        return $bitmask & ~$flag->value;
    }
}
```

The Room model should have convenience methods:

```php
public function isPrivate(): bool
{
    return RoomType::has($this->type, RoomType::Private);
}

public function isArchived(): bool
{
    return RoomType::has($this->type, RoomType::Archived);
}

public function isAnnouncements(): bool
{
    return RoomType::has($this->type, RoomType::Announcements);
}
```

---

## Configuration (config/bonfire.php)

```php
<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Tenant ID Resolver
    |--------------------------------------------------------------------------
    |
    | Returns current tenant ID or null for non-tenant apps. All Bonfire
    | records will be scoped to this tenant ID.
    |
    */
    'tenant_id' => fn () => null,

    /*
    |--------------------------------------------------------------------------
    | Attachment Storage
    |--------------------------------------------------------------------------
    */
    'disk' => 'public',
    'max_attachment_size_kb' => 10240,
    'allowed_attachment_types' => ['image/*', 'application/pdf'],

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */
    'route_prefix' => 'bonfire',
    'route_middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notification_channels' => ['database'],

    /*
    |--------------------------------------------------------------------------
    | Member Profile URL
    |--------------------------------------------------------------------------
    |
    | Given a Member model, return a URL to their profile page, or null.
    | Used to make mention chips clickable.
    |
    */
    'user_profile_url' => fn ($member) => null,

    /*
    |--------------------------------------------------------------------------
    | Link Preview Settings
    |--------------------------------------------------------------------------
    */
    'link_preview_enabled' => true,
    'link_preview_timeout_seconds' => 5,

    /*
    |--------------------------------------------------------------------------
    | Member Resolution
    |--------------------------------------------------------------------------
    |
    | Given the authenticated user, return the corresponding Member model.
    | Default performs polymorphic lookup via Auth::user().
    |
    */
    'resolve_member' => fn () => \ArtisanBuild\Bonfire\Facades\Bonfire::memberFor(
        \Illuminate\Support\Facades\Auth::user()
    ),
];
```

---

## Facade API (BonfireManager.php)

```php
<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire;

use ArtisanBuild\Bonfire\Enums\BonfireRole;
use ArtisanBuild\Bonfire\Models\Member;
use ArtisanBuild\Bonfire\Models\Message;
use ArtisanBuild\Bonfire\Models\Room;
use Illuminate\Database\Eloquent\Model;

/**
 * Main API for host application integration with Bonfire.
 */
final class BonfireManager
{
    /**
     * Create or update a Member record for any Eloquent model.
     *
     * Always syncs display_name and avatar_url. This method is idempotent
     * and safe to call on every login or in model observers.
     */
    public function ensureMember(
        Model $memberable,
        string $displayName,
        ?string $avatarUrl = null,
        BonfireRole $role = BonfireRole::Member,
    ): Member;

    /**
     * Return the Member record for a given host-app model, or null.
     */
    public function memberFor(?Model $model): ?Member;

    /**
     * Post a message programmatically on behalf of any Member.
     *
     * Use this for bots and AI agents. Fires MessagePosted broadcast event.
     */
    public function postAs(
        Member $member,
        Room $room,
        string $body,
        ?Message $parent = null,
    ): Message;

    /**
     * Change a Member's role.
     */
    public function promote(Member $member, BonfireRole $role): Member;

    /**
     * Deactivate a member. They lose all write access immediately.
     */
    public function deactivate(Member $member): Member;

    /**
     * Reactivate a member. Restores write access.
     */
    public function reactivate(Member $member): Member;

    /**
     * Get the current tenant ID from the configured resolver.
     */
    public function tenantId(): ?int;
}
```

---

## Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | /{prefix} | bonfire.index | Room list |
| GET | /{prefix}/{room:slug} | bonfire.room.show | Single room view |
| GET | /{prefix}/admin | bonfire.admin.index | Admin panel (admin role required) |
| GET | /{prefix}/attachments/{attachment} | bonfire.attachments.show | Serve attachment with access control |

All Livewire component interactions operate via `wire:` mechanism.

---

## Real-Time Events

### Channel Naming

- `bonfire.room.{room_id}` - Presence channel for a room
- `bonfire.member.{member_id}` - Private channel for per-member notifications

### Event Classes

| Event | Channel | Triggered When |
|-------|---------|----------------|
| MessagePosted | room.{room_id} | New root or reply message saved |
| MessageDeleted | room.{room_id} | Message soft-deleted |
| ReactionToggled | room.{room_id} | Reaction added or removed |
| UserTyping (whispered) | room.{room_id} | Member types in composer |
| MentionedInMessage | member.{member_id} | Member mentioned in message |

### Livewire Listeners

- **RoomShow**: Listens for MessagePosted, MessageDeleted, ReactionToggled
- **ThreadPanel**: Listens for MessagePosted filtered to parent_id
- **RoomIndex**: Listens on member channel for mention notifications

---

## Feature Specifications

### 1. Member Registration

The host app calls `Bonfire::ensureMember()` to create/update Member records:

```php
// Human user
$member = Bonfire::ensureMember(
    memberable: $user,
    displayName: $user->name,
    avatarUrl: $user->avatar_url,
);

// Bot or AI agent
$member = Bonfire::ensureMember(
    memberable: $bot,
    displayName: 'Helpdesk Bot',
    avatarUrl: asset('images/bot-avatar.png'),
);
```

**Optional Observer Pattern:**

```php
// In AppServiceProvider::boot()
User::observe(BonfireMemberObserver::class);
```

The observer expects the model to implement `bonfireDisplayName()` and `bonfireAvatarUrl()` (provided by `HasBonfireProfile` trait).

### 2. Room List (RoomIndex Component)

- Active members see all public rooms + private rooms they belong to
- Unauthenticated visitors see public rooms listed (read-only)
- Each room shows: name, description (truncated), has_unread indicator
- Unread tracking: session/cookie `last_read_at` per room for public rooms
- Archived rooms sorted to bottom with visual distinction
- Announcements rooms show megaphone icon

### 3. Room View (RoomShow Component)

- Message list with infinite scroll (reverse-chronological pagination)
- Pins to bottom on load and new messages (unless user scrolled up)
- "New messages" chip when new messages arrive while scrolled up
- Thread indicators on messages with replies (avatars + count)

### 4. Message Composer (MessageComposer Component)

- Markdown input with toolbar: bold, italic, inline code, link
- Enter sends, Shift+Enter for newline
- Hint text: "Shift+↵ for new line"
- Empty messages rejected client-side
- File upload via picker or drag-and-drop
- Progress bar during upload, send disabled until complete
- `@` triggers member autocomplete dropdown
- Hidden (with explanation) in Announcements rooms for non-moderators
- Hidden in Archived rooms for everyone

### 5. Message Rendering

- Markdown rendered server-side via league/commonmark (XSS-safe)
- Raw HTML passthrough NEVER permitted
- `@display_name` rendered as styled chips (linked if profile URL configured)
- Code blocks use highlight.js via CDN
- URLs trigger FetchLinkPreview job
- Link preview card: thumbnail left, title/description/site_name right

### 6. Message Deletion

- Members delete own messages
- Moderators/admins delete any message
- Soft delete: body replaced with tombstone "This message was deleted."
- Reactions hidden on deleted messages
- Thread replies remain visible with tombstone parent

### 7. Threads (ThreadPanel Component)

- Reply action opens slide-in panel from right
- Shows original message at top + chronological replies
- One thread panel open at a time
- Replies have parent_id set, cannot be replied to themselves
- Root messages with replies show thread indicator

### 8. Reactions

- Single like/heart toggle per member per message
- Count displayed; filled icon when > 0, outlined when 0
- ReactionToggled broadcasts for real-time updates
- Hidden on deleted messages

### 9. Link Previews (FetchLinkPreview Job)

- First URL in message triggers fetch
- Extracts og:title, og:description, og:image, og:site_name
- Two retries with exponential backoff
- Failed permanently after retries (failed = true)
- No preview on deleted messages

### 10. Attachments

- Livewire 4 chunked file upload
- Allowed MIME types and max size from config
- Images displayed inline as thumbnails (click for full)
- Non-images as download card (filename, icon, size)
- Storage: `bonfire/{tenant_id}/{room_id}/` (tenant_id = 0 when null)
- Served through AttachmentController with access control

### 11. Mentions

- `@` triggers autocomplete searching members table
- On save, `@display_name` tokens parsed and written to mentions table
- MentionedInMessage notification sent (channels from config)
- Members cannot mention themselves
- Bots can mention humans and vice versa

### 12. Typing Indicators

- Whispered Echo event on room presence channel
- "Name is typing..." or "N people are typing..."
- Disappears after 3 seconds inactivity or message sent
- Never persisted

### 13. Access Control

| Visitor Type | Public Rooms | Private Rooms |
|--------------|--------------|---------------|
| Unauthenticated | Read-only | Redirect to login |
| Authenticated, no Member | Read-only (guest) | 403 |
| Active Member | Full access | Full access if in pivot |
| Inactive Member | Read-only | No access |
| Moderator | + delete any message | Same |
| Admin | + room/member management | Same |

### 14. Admin Panel (AdminPanel Component)

Available at `{prefix}/admin` for admin-role members only.

**Features:**
- Room management: create, edit name/description, toggle type flags
- Member management: list members, change roles, activate/deactivate

**Note:** Message moderation is inline (moderators see delete button on all messages in room view).

---

## Artisan Commands

### bonfire:install

```bash
php artisan bonfire:install
```

- Publishes config
- Runs migrations
- Safe to run on existing installs (migrations are idempotent)

### bonfire:create-room

```bash
php artisan bonfire:create-room "General Chat" --type=0
php artisan bonfire:create-room "Team Private" --type=1
php artisan bonfire:create-room "Announcements" --type=4
```

- Creates room from CLI
- Pass type bitmask value for flags
- Useful in seeders and CI

---

## Test Requirements

The package must ship with a comprehensive Pest test suite. Tests use SQLite for isolation.

### Required Test Coverage

1. **MemberRegistrationTest**
   - ensureMember creates new member
   - ensureMember updates existing member
   - Polymorphic lookup works for different model types
   - Role assignment on creation

2. **RoomAccessTest**
   - Public room accessible by all members
   - Private room only accessible by pivot members
   - Archived room is read-only
   - Announcements room restricts posting
   - Unauthenticated access to public rooms (read-only)
   - 403 for private room without membership

3. **MessagePostingTest**
   - Member can post to accessible room
   - Bot posts via postAs()
   - Cannot post to archived room
   - Cannot post root message to announcements room (unless moderator)
   - Message broadcasts MessagePosted event

4. **ThreadsTest**
   - Can reply to root message
   - Cannot reply to a reply
   - Thread indicator shows replier avatars and count
   - ThreadPanel shows replies in chronological order

5. **ReactionsTest**
   - Toggle adds reaction
   - Toggle removes existing reaction
   - One reaction per member per message
   - Reactions hidden on deleted messages

6. **MentionsTest**
   - @display_name parsed and stored in mentions table
   - MentionedInMessage notification sent
   - Cannot mention self
   - Bot can mention human

7. **AttachmentsTest**
   - File upload stores to correct path
   - Access control enforced on serve
   - MIME type validation
   - Size limit validation

8. **RoleEnforcementTest**
   - Member cannot delete others' messages
   - Moderator can delete any message
   - Only admin can create rooms
   - Only admin can change room type
   - Only admin can manage member roles

### Unit Tests

1. **RoomTypeEnumTest**
   - Bitmask has() works correctly
   - Bitmask add() works correctly
   - Bitmask remove() works correctly
   - Combined flags work (e.g., 5 = Private | Announcements)

2. **MarkdownRendererTest**
   - Renders markdown to HTML
   - Escapes raw HTML (XSS prevention)
   - Handles code blocks
   - Handles mentions

3. **MemberModelTest**
   - Polymorphic relationship works
   - Scopes by tenant_id when set
   - is_active filtering works

---

## Implementation Tasks

### Phase 1: Foundation

- [ ] **1.1** Update composer.json with proper dependencies (livewire ^4.0, league/commonmark ^2.0)
- [ ] **1.2** Create all database migrations (8 files)
- [ ] **1.3** Create Eloquent models with relationships (7 models)
- [ ] **1.4** Create BonfireRole enum
- [ ] **1.5** Create RoomType bitmask enum with helpers
- [ ] **1.6** Create BonfireManager class with all facade methods
- [ ] **1.7** Create Bonfire facade
- [ ] **1.8** Implement full config/bonfire.php
- [ ] **1.9** Update BonfireServiceProvider (config, migrations, routes, Livewire components)
- [ ] **1.10** Create HasBonfireProfile trait
- [ ] **1.11** Create BonfireMemberObserver

### Phase 2: Core Livewire Components (MFC)

- [ ] **2.1** Create layouts/bonfire.blade.php
- [ ] **2.2** Create RoomIndex MFC component
- [ ] **2.3** Create RoomShow MFC component
- [ ] **2.4** Create MessageComposer MFC component (with @mention autocomplete)
- [ ] **2.5** Create MessageList MFC component (with infinite scroll)
- [ ] **2.6** Create ThreadPanel MFC component

### Phase 3: Supporting Features

- [ ] **3.1** Create MarkdownRenderer (league/commonmark, XSS-safe)
- [ ] **3.2** Create FetchLinkPreview job (with retries)
- [ ] **3.3** Create AttachmentController (with access control)
- [ ] **3.4** Create all broadcast events (4 event classes)
- [ ] **3.5** Create MentionedInMessage notification
- [ ] **3.6** Implement typing indicators (whispered events)
- [ ] **3.7** Implement unread tracking (session/cookie for public rooms)

### Phase 4: Admin Panel

- [ ] **4.1** Create AdminPanel MFC component
- [ ] **4.2** Implement room CRUD in admin
- [ ] **4.3** Implement member management in admin

### Phase 5: CLI & Polish

- [ ] **5.1** Create bonfire:install command
- [ ] **5.2** Create bonfire:create-room command
- [ ] **5.3** Add inline delete buttons for moderators
- [ ] **5.4** Final UI polish with Flux components

### Phase 6: Testing

- [ ] **6.1** Set up Pest with SQLite TestCase
- [ ] **6.2** Write MemberRegistrationTest
- [ ] **6.3** Write RoomAccessTest
- [ ] **6.4** Write MessagePostingTest
- [ ] **6.5** Write ThreadsTest
- [ ] **6.6** Write ReactionsTest
- [ ] **6.7** Write MentionsTest
- [ ] **6.8** Write AttachmentsTest
- [ ] **6.9** Write RoleEnforcementTest
- [ ] **6.10** Write unit tests (RoomTypeEnumTest, MarkdownRendererTest, MemberModelTest)

---

## Testing in a Fresh Laravel App

After building the package, test it in a real application:

### 1. Create a fresh Laravel app

```bash
laravel new bonfire-test --git
cd bonfire-test
```

### 2. Install required dependencies

```bash
composer require livewire/livewire:^4.0 livewire/flux livewire/flux-pro
composer require laravel/reverb --dev
php artisan install:broadcasting
```

### 3. Add Bonfire as a path repository

In `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../kibble/packages/bonfire"
        }
    ]
}
```

Then:

```bash
composer require artisan-build/bonfire:*
```

### 4. Install and configure Bonfire

```bash
php artisan bonfire:install
```

### 5. Set up Member registration

In `app/Models/User.php`:

```php
use ArtisanBuild\Bonfire\Traits\HasBonfireProfile;

class User extends Authenticatable
{
    use HasBonfireProfile;
}
```

In `app/Providers/AppServiceProvider.php`:

```php
use ArtisanBuild\Bonfire\Observers\BonfireMemberObserver;
use App\Models\User;

public function boot(): void
{
    User::observe(BonfireMemberObserver::class);
}
```

### 6. Create an admin user and room

```bash
php artisan tinker
```

```php
$user = User::factory()->create(['name' => 'Admin']);
$member = Bonfire::ensureMember($user, $user->name, null, BonfireRole::Admin);
Bonfire::promote($member, BonfireRole::Admin);
```

```bash
php artisan bonfire:create-room "General Chat"
```

### 7. Start services and test

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Reverb WebSocket server
php artisan reverb:start

# Terminal 3: Queue worker
php artisan queue:work
```

Visit `http://localhost:8000/bonfire` and test:
- Room list displays
- Can enter a room
- Can post messages
- Real-time updates work (open two browser windows)
- Threads work
- Reactions work
- Admin panel accessible

---

## Agent Instructions for Filament Admin

If the consuming app uses Filament, the owner can ask an AI agent to build a Filament admin panel:

> "Build a Filament admin panel for Bonfire that includes:
> - RoomResource: CRUD for rooms with type flag toggles
> - MemberResource: List members, change roles, toggle active status
> - MessageResource: Read-only list for moderation, with soft delete action
>
> Use the Bonfire models from `ArtisanBuild\Bonfire\Models\*`. The package handles all business logic via the models - just expose CRUD operations."

---

## Success Criteria

Bonfire v1.0 is complete when:

1. A developer can add it to a fresh Laravel 11 + Livewire 4 + Flux UI Pro app and have functioning chat in under 15 minutes
2. Human users, bots, and AI agents can all post with visually identical messages
3. All real-time features work with multiple concurrent browser sessions
4. Role-based access control is enforced
5. Comprehensive Pest test suite passes
6. No raw user input is ever rendered as unescaped HTML
7. Package never references host app's users table directly
8. Routes can be prefixed/renamed via config without modifying source
