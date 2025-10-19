# API Specification

This is the API specification for the spec detailed in @.agent-os/specs/2025-10-18-forge-sdk/spec.md

> Created: 2025-10-18
> Version: 1.0.0

## API Overview

**Base URL**: `https://forge.laravel.com/api`
**Authentication**: Bearer token via `Authorization: Bearer {token}` header
**Rate Limit**: 60 requests per minute
**Response Format**: JSON
**API Spec**: JSON:API compliant (new Forge API)

## Local API Documentation

**CRITICAL**: All endpoint paths, parameters, request/response structures, and behaviors MUST be verified against the **local API documentation** at `@.agent-os/specs/2025-10-18-forge-sdk/api-docs/`.

**DO NOT** use:
- Memory or existing knowledge of the Forge API
- Web searches or online documentation (may be outdated)
- Any other source besides the local api-docs folder

The local documentation is the authoritative source. See `api-docs/README.md` for a complete index of all endpoint documentation organized by category.

## Resource Groups & Endpoints

The SDK must implement all resources in the following order, matching the Forge API documentation structure:

### 1. Background Processes

**Purpose**: Manage long-running background processes (daemons) on servers

**Endpoints to implement**:
- List background processes for a server
- Get specific background process details
- Create a new background process
- Update background process
- Delete background process
- Restart background process

---

### 2. Commands

**Purpose**: Execute commands on servers

**Endpoints to implement**:
- List commands for a server
- Get command details
- Run a command on a server
- Get command output/status

---

### 3. Databases

**Purpose**: Manage databases and database users on servers

**Endpoints to implement**:
- List databases on a server
- Get database details
- Create a database
- Update database
- Delete database
- List database users
- Create database user
- Update database user
- Delete database user

---

### 4. Deployments

**Purpose**: Manage site deployments

**Endpoints to implement**:
- Get deployment status
- Trigger deployment
- Get deployment log
- Get deployment script
- Update deployment script
- Enable/disable quick deploy
- Reset deployment state
- List deployment history

---

### 5. Firewall Rules

**Purpose**: Manage server firewall rules

**Endpoints to implement**:
- List firewall rules for a server
- Get firewall rule details
- Create firewall rule
- Delete firewall rule

---

### 6. Integrations

**Purpose**: Manage source control and other integrations

**Endpoints to implement**:
- List integrations
- Get integration details
- Create integration (GitHub, GitLab, Bitbucket, etc.)
- Delete integration

---

### 7. Logs

**Purpose**: Access server and application logs

**Endpoints to implement**:
- Get server logs
- Get application logs (nginx, PHP, etc.)

---

### 8. Monitors

**Purpose**: Manage server monitoring

**Endpoints to implement**:
- List monitors for a server
- Get monitor details
- Create monitor
- Update monitor
- Delete monitor

---

### 9. Nginx

**Purpose**: Manage Nginx configuration

**Endpoints to implement**:
- Get Nginx template for site
- Update Nginx template
- Reset Nginx template to default

---

### 10. Organizations

**Purpose**: Manage Forge organizations (replaces Circles from old API)

**Endpoints to implement**:
- List organizations
- Get organization details
- Create organization
- Update organization
- Delete organization
- List organization members
- Add member to organization
- Remove member from organization

---

### 11. Providers

**Purpose**: Manage cloud provider credentials

**Endpoints to implement**:
- List providers
- Get provider details
- Create provider credentials (DigitalOcean, AWS, Linode, Vultr, Hetzner, etc.)
- Delete provider credentials

---

### 12. Recipes

**Purpose**: Manage server recipes (reusable scripts)

**Endpoints to implement**:
- List recipes
- Get recipe details
- Create recipe
- Update recipe
- Delete recipe
- Run recipe on server

---

### 13. Redirect Rules

**Purpose**: Manage site redirect rules

**Endpoints to implement**:
- List redirect rules for a site
- Get redirect rule details
- Create redirect rule
- Update redirect rule
- Delete redirect rule

---

### 14. Roles

**Purpose**: Manage organization roles and permissions

**Endpoints to implement**:
- List roles
- Get role details
- Create role
- Update role
- Delete role

---

### 15. SSH Keys

**Purpose**: Manage SSH keys on servers

**Endpoints to implement**:
- List SSH keys for a server
- Get SSH key details
- Create SSH key
- Delete SSH key

---

### 16. Scheduled Jobs

**Purpose**: Manage cron jobs on servers

**Endpoints to implement**:
- List scheduled jobs for a server
- Get scheduled job details
- Create scheduled job
- Update scheduled job
- Delete scheduled job

---

### 17. Security Rules

**Purpose**: Manage server security rules

**Endpoints to implement**:
- List security rules for a server
- Get security rule details
- Create security rule
- Update security rule
- Delete security rule

---

### 18. Server Credentials

**Purpose**: Manage credentials stored on servers (database passwords, API keys, etc.)

**Endpoints to implement**:
- List credentials for a server
- Get credential details
- Create credential
- Update credential
- Delete credential

---

### 19. Servers

**Purpose**: Manage Forge servers

**Endpoints to implement**:
- List servers
- Get server details
- Create server
- Update server
- Delete server
- Reboot server
- Revoke Forge access to server
- Reconnect to server
- Reactivate server
- Get server services status
- Restart server service
- Stop server service
- Install service on server

---

### 20. Sites

**Purpose**: Manage sites on servers

**Endpoints to implement**:
- List sites on a server
- Get site details
- Create site
- Update site
- Delete site
- Install Git repository
- Update Git repository
- Remove Git repository
- Install WordPress
- Remove WordPress
- Get environment file
- Update environment file
- Get site logs
- Enable/disable isolation
- Change PHP version

---

### 21. Teams

**Purpose**: Manage teams within organizations

**Endpoints to implement**:
- List teams
- Get team details
- Create team
- Update team
- Delete team
- List team members
- Add member to team
- Remove member from team

---

### 22. User

**Purpose**: Manage authenticated user account

**Endpoints to implement**:
- Get current user details
- Update user profile
- Get API tokens
- Create API token
- Delete API token

---

## SSL Certificates

**Note**: SSL certificates are managed as a sub-resource of Sites.

**Endpoints to implement**:
- List certificates for a site
- Get certificate details
- Install Let's Encrypt certificate
- Install custom certificate
- Activate certificate
- Delete certificate

---

## Enums Required

Based on the API resources above, we'll need the following enums (exact values must be verified from API documentation during implementation):

### Server-Related Enums
- `CloudProvider` - ocean (DigitalOcean), linode, aws, vultr, hetzner, custom
- `Region` - Region codes per provider (dynamic)
- `ServerSize` - Size codes per provider (dynamic)
- `ServerType` - app, web, database, cache, worker, load-balancer
- `PhpVersion` - php74, php80, php81, php82, php83, php84
- `ServiceName` - nginx, mysql, postgres, redis, memcached, etc.

### Database-Related Enums
- `DatabaseType` - mysql, mysql8, postgres, mariadb

### Site-Related Enums
- `ProjectType` - php, laravel, html, symfony, spa
- `SiteStatus` - installing, installed

### SSL Certificate Enums
- `CertificateType` - letsencrypt, existing, clone

### Scheduled Job Enums
- `JobFrequency` - minutely, hourly, nightly, weekly, monthly, reboot, custom

### Integration Enums
- `IntegrationType` - github, gitlab, bitbucket, custom

### Monitor Enums
- `MonitorType` - cpu_load, used_disk_space, memory_usage

### Firewall Rule Enums
- `FirewallRuleType` - allow, deny
- `FirewallRulePort` - Common ports or custom

### Log Type Enums
- `LogType` - nginx_access, nginx_error, php, database

---

## HTTP Methods by Operation

Following REST conventions:
- **GET** - List and retrieve resources
- **POST** - Create new resources
- **PUT/PATCH** - Update existing resources
- **DELETE** - Remove resources

---

## Standard Response Codes

- `200` - Success (GET, PUT/PATCH operations)
- `201` - Created (POST operations)
- `204` - No Content (DELETE operations)
- `400` - Bad Request
- `401` - Unauthorized (invalid or missing API token)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `422` - Unprocessable Entity (validation errors)
- `429` - Too Many Requests (rate limit exceeded)
- `500` - Internal Server Error
- `503` - Service Unavailable (maintenance mode)

---

## JSON:API Compliance

The new Forge API follows JSON:API specification. This means:

- Resources have `type` and `id` fields
- Responses include `data` wrapper
- Relationships are structured per JSON:API spec
- Pagination follows JSON:API links pattern
- Filtering and sorting use JSON:API conventions
- Includes (eager loading) use JSON:API `include` parameter

The SDK should handle JSON:API structure internally and provide clean resource objects to users.

---

## Implementation Priority

During implementation, resources should be tackled in this order:

**Phase 1 - Core Resources** (most commonly used):
1. Servers
2. Sites
3. Deployments
4. SSL Certificates (via Sites)
5. Databases

**Phase 2 - Server Management**:
6. Firewall Rules
7. SSH Keys
8. Scheduled Jobs
9. Background Processes

**Phase 3 - Organization & Access**:
10. Organizations
11. Teams
12. Roles
13. User

**Phase 4 - Advanced Features**:
14. Commands
15. Nginx
16. Redirect Rules
17. Logs
18. Monitors

**Phase 5 - Integrations & Utilities**:
19. Providers
20. Integrations
21. Recipes
22. Server Credentials
23. Security Rules

---

## Testing Strategy Per Resource

For each resource, create:

1. **Unit Tests** - Validate request construction, parameter validation, enum usage
2. **Fixture Responses** - JSON response examples for each endpoint
3. **Integration Tests** - Full request/response cycle with mocked HTTP
4. **Command Tests** - Test artisan command for manual testing
5. **Documentation** - Usage examples in README for each resource

---

## Command Naming Convention

Manual testing commands should follow this pattern:

```bash
php artisan forge:test-servers {action}
php artisan forge:test-sites {action}
php artisan forge:test-deployments {action}
# etc.
```

Where `{action}` is: list, show, create, update, delete, or resource-specific actions.
