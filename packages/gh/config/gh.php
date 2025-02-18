<?php

/*
 * This configuration file contains all the possible options for every command offered by the CLI.
 * We've optimized these defaults for our own use. Please carefully read the GitHub API documentation
 * to determine whether these default values are right for you.
 */

return [
    'default_options' => [
        'alias' => [
            'set' => [
                // '--shell', // Use shell evaluation
            ],
            'delete' => [],
            'list' => [],
        ],

        'auth' => [
            'login' => [
                // '--hostname {string}', // GitHub hostname
                // '--scopes {string}',   // Permissions for token
                // '--web', // Use web browser for authentication
            ],
            'logout' => [],
            'status' => [],
            'token' => [],
        ],

        'browse' => [
            // '--branch {string}',
            // '--commit {string}',
        ],

        'gist' => [
            'create' => [
                // '--desc {string}',   // Description for the gist
                // '--public',         // Make gist public
            ],
            'list' => [
                // '--limit {int}',     // Number of gists to list
                // '--public',         // Only public gists
            ],
            'view' => [
                // '--raw',            // Display raw content
            ],
            'edit' => [],
            'delete' => [],
        ],

        'issue' => [
            'create' => [
                // '--assignee {string}', // Assign user
                // '--label {string}',    // Label for issue
                // '--milestone {string}' // Milestone for issue
            ],
            'list' => [
                // '--assignee {string}',
                // '--label {string}',
                // '--milestone {string}',
                // '--state {string}',   // open, closed, or all
            ],
            'view' => [],
            'close' => [],
            'reopen' => [],
            'edit' => [],
        ],

        'pr' => [
            'create' => [
                // '--base {string}',       // Base branch
                // '--body {string}',       // PR body
                // '--title {string}',      // PR title
                // '--draft',               // Create draft PR
            ],
            'list' => [
                // '--state {string}',      // open, closed, or all
                // '--limit {int}',         // Number of PRs to list
            ],
            'view' => [],
            'close' => [],
            'reopen' => [],
            'edit' => [],
            'merge' => [
                // '--auto',               // Enable auto-merge
                // '--merge',              // Use merge commit
                // '--rebase',             // Use rebase strategy
                // '--squash',             // Use squash merge
            ],
        ],

        'release' => [
            'create' => [
                // '--notes {string}',      // Release notes
                // '--title {string}',      // Release title
                // '--prerelease',          // Mark as prerelease
                // '--draft',               // Mark as draft
            ],
            'delete' => [],
            'list' => [
                // '--limit {int}',         // Number of releases to list
            ],
            'view' => [],
            'edit' => [],
            'upload-asset' => [
                // '--label {string}',      // Label for the asset
            ],
        ],

        'repo' => [
            'archive' => [
                '--yes', // Skip the confirmation prompt
            ],
            'clone' => [
                // '--upstream-remote-name <string> (upstream by default)',
            ],
            'create' => [
                '--add-readme',
                // '--clone',
                // '--description {string}',
                '--disable-issues',
                '--disable-wiki',
                // '--gitignore {string}',
                // '--homepage {string}',
                // '--include-all-branches',
                // '--internal',
                '--license mit',
                // '--private',
                '--public',
                // '--push',
                // '--remote {string}',
                // '--source {string}',
                // '--team {string}',
                // '--template {string}',
            ],
            'delete' => [
                '--yes',
            ],
            'deploy-key' => [
                // '--repo <[HOST/]OWNER/REPO>',
                // '--allow-write' // (add)
                // '--title' // (add)
                // '--jq' // (list)
                // '--json' // (list)
                // '--template' // (list)
            ],
            'view' => [
                // '--web', // Open in browser
            ],
            'fork' => [
                // '--clone', // Clone repo after fork
            ],
            'list' => [
                // '--limit {int}', // Number of repos to list
                // '--visibility {string}', // public, private, internal
            ],
            'set-default' => [
                // '--unset', // Unset current default
                // '--view', // View current default
            ],
            'unarchive' => [
                '--yes',
            ],
        ],

        'secret' => [
            'set' => [
                // '--env {string}', // Environment name
            ],
            'list' => [
                // '--env {string}',
            ],
            'delete' => [],
        ],
    ],
];
