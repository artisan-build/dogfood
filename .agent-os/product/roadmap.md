# Product Roadmap

> Last Updated: 2025-10-13
> Version: 1.0.0
> Status: Active Development

## Phase 0: Already Completed

The following features have been implemented and are in production:

- [x] **Monorepo Infrastructure** - Complete Laravel 11 monorepo with 22 packages managed via Composer path repositories
- [x] **Package Splitting System** - `php artisan kibble:split` command automatically deploys packages to individual GitHub repositories
- [x] **Team-Based Architecture** - Multi-tenancy with Team model, ownership, memberships, and role-based access
- [x] **User Authentication** - Email verification, password hashing, profile management with photo URLs
- [x] **Event Sourcing Foundation** - TeamState and UserState using Verbs pattern via Adverbs package
- [x] **Dashboard Interface** - Livewire-powered dashboard component for authenticated users
- [x] **Comprehensive Testing** - Pest PHP suite with parallel execution support across all packages
- [x] **Code Quality Automation** - Unified `composer ready` command running Rector, Pint, PHPStan, tests
- [x] **Package CI Automation** - Shell script to generate GitHub Actions workflows for all packages
- [x] **Development Tooling** - Multi-process dev server with concurrently (server, queue, logs, vite)
- [x] **Flux UI Integration** - Complete Flux UI Pro component library implementation
- [x] **Dual Testing Context** - Each package supports both monorepo and isolated testing
- [x] **IDE Support** - Laravel IDE Helper with automatic model documentation generation

## Phase 1: October 2025 - Documentation & Cleanup (Current)

**Goal:** Improve package documentation and remove deprecated features
**Success Criteria:** All package READMEs conform to open-source standards, deprecated packages removed

### Must-Have Features

- [ ] **Package README Updates** - Update all 22 package README files with contribution guidelines, installation instructions, and usage examples `M`
- [ ] **Contribution Documentation** - Add clear instructions in each README explaining the monorepo workflow (clone kibble, not individual package) `S`
- [ ] **GitHub PR Auto-Close** - Configure individual package repositories to auto-close PRs with contribution instructions `S`
- [ ] **Remove HallwayCore Package** - Archive hallway-core package in GitHub, remove from kibble `XS`
- [ ] **Remove HallwayFlux Package** - Archive hallway-flux package in GitHub, remove from kibble `XS`

### Should-Have Features

- [ ] **Internal-Only Package Markers** - Update READMEs for internal-only packages to clarify they're not intended for external use `XS`
- [ ] **Agent OS Documentation** - Complete Agent OS installation and product documentation (in progress) `S`

### Dependencies

- Package README template/standards
- GitHub repository access for auto-close configuration

## Phase 2: New Package Development (Q4 2025)

**Goal:** Add RunOnce package for production deployment automation
**Success Criteria:** RunOnce package published, tested, and added to starter kit

### Must-Have Features

- [ ] **RunOnce Package** - Create package providing `php artisan run-once` command that executes all classes in `App\Actions\RunOnce` directory `M`
- [ ] **Idempotent Action Pattern** - Document pattern for writing invokable classes with idempotent business logic `S`
- [ ] **Package Tests** - Comprehensive test suite for RunOnce package `M`
- [ ] **Package Documentation** - README with usage examples, caveats, and best practices `S`
- [ ] **Starter Kit Integration** - Add RunOnce to Artisan Build starter kit `XS`

### Should-Have Features

- [ ] **Example RunOnce Actions** - Provide 2-3 example actions demonstrating common use cases `S`
- [ ] **Deploy Script Example** - Document how to add run-once to deployment workflow `XS`

### Dependencies

- Clear use case requirements for RunOnce package
- Starter kit repository access

## Phase 3: As-Needed Package Improvements (Ongoing)

**Goal:** Continuously improve existing packages based on internal needs
**Success Criteria:** Packages remain stable, well-tested, and serve internal development needs

### Approach

This phase is intentionally unstructured. Package improvements are driven by:
- Bugs discovered during usage
- New features needed for internal projects
- Performance optimizations identified through use
- Refactoring opportunities for better maintainability

### Process

1. Identify need during internal project work
2. Create spec in kibble using Agent OS
3. Implement changes in monorepo
4. Verify with `composer ready`
5. Merge to main
6. Automatic split to individual package repos

### Active Package Areas

- **Till/Till-Stripe:** Subscription management improvements as needed
- **Turbulence:** Additional testing utilities as patterns emerge
- **Flux-Themes:** Theme refinements for internal projects
- **Verbs-Flux:** Flux UI components for event sourcing patterns
- **Adverbs:** Core event sourcing enhancements
- **Claude Code:** Integration improvements as Claude Code evolves
- **Agent OS Installer:** Installation and setup improvements

## Phase 4: External Contributor Experience (2026)

**Goal:** Make it easier for external contributors to work with Kibble packages
**Success Criteria:** At least one successful external contribution, clear contribution path

### Must-Have Features

- [ ] **Contributor Guide** - Comprehensive guide in main repository explaining monorepo workflow `M`
- [ ] **Development Environment Setup** - Automated setup script for new contributors `M`
- [ ] **Flux License Handling** - Document Flux UI Pro license requirements and workarounds for contributors `S`
- [ ] **Issue Templates** - GitHub issue templates directing contributors to correct repository `S`

### Should-Have Features

- [ ] **Video Walkthrough** - Screen recording demonstrating contribution workflow `M`
- [ ] **Discord/Slack Community** - Community space for contributors to ask questions `L`
- [ ] **First-Timer Issues** - Label and document good first issues for new contributors `M`

### Dependencies

- Stabilized package ecosystem
- Bandwidth for contributor support

## Phase 5: Package Ecosystem Expansion (Future)

**Goal:** Grow the package ecosystem with additional opinionated tools
**Success Criteria:** 5+ new packages providing value to internal projects and aligned external users

### Potential Packages (Not Committed)

- **Form Builder:** Opinionated form builder for Flux UI + Livewire
- **Command Bus:** Enhanced command pattern implementation
- **Specification Pattern:** Reusable query specifications for Eloquent
- **API Resources:** Convention-based API resource transformations
- **Audit Trail:** Enhanced audit logging beyond Verbs events
- **Feature Flags:** Simple, opinionated feature flag system

### Dependencies

- Internal project needs driving package requirements
- Proven patterns worth extracting to packages

---

## Roadmap Philosophy

Kibble's roadmap is intentionally flexible and need-driven rather than prescriptive. The primary measure of success is: **Does this support our internal development workflow efficiently?**

External use is a secondary benefit, not the primary driver.
