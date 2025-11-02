<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Agent OS Installer Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the Agent OS Installer
    | package. This package helps install Agent OS and related code quality
    | tools into Laravel applications.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Agent OS Web Viewer Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the web interface for browsing Agent OS documentation.
    |
    */
    'viewer' => [
        // Enable or disable the web viewer
        'enabled' => env('AGENT_OS_VIEWER_ENABLED', true),

        // Route prefix for the viewer (e.g., /agent-os, /documentation, /docs)
        'route_prefix' => env('AGENT_OS_ROUTE_PREFIX', 'agent-os'),

        // Middleware to apply to viewer routes
        'middleware' => ['web'],

        // Optional gate name to check for access control
        'gate' => null,

        // Paths to scan for markdown documentation
        'paths' => [
            '.agent-os' => 'Agent OS Documentation',
            // Add additional directories as needed:
            // 'docs' => 'User Documentation',
        ],

        // Default view when accessing the index route ('product' or 'readme')
        'default_view' => 'product',
    ],
];
