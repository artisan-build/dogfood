#!/usr/bin/env node

/**
 * Script to fetch complete Laravel Forge API documentation
 *
 * This version uses a predefined list of known API endpoints
 * since the documentation uses dynamic navigation.
 */

import https from 'https';
import http from 'http';
import fs from 'fs';
import path from 'path';
import { URL, fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const BASE_URL = 'https://forge.laravel.com';
const OUTPUT_DIR = path.join(__dirname, 'api-docs');

// Comprehensive list of Forge API documentation pages
const API_PAGES = [
    // Getting Started
    '/docs/api-reference/introduction',
    '/docs/api-reference/pagination',
    '/docs/api-reference/rate-limiting',
    '/docs/api-reference/filtering',
    '/docs/api-reference/relationships',

    // Organizations
    '/docs/api-reference/organizations/list-organizations',
    '/docs/api-reference/organizations/get-organization',

    // Servers
    '/docs/api-reference/servers/list-servers',
    '/docs/api-reference/servers/create-server',
    '/docs/api-reference/servers/get-server',
    '/docs/api-reference/servers/update-server',
    '/docs/api-reference/servers/delete-server',
    '/docs/api-reference/servers/reboot-server',
    '/docs/api-reference/servers/revoke-access',
    '/docs/api-reference/servers/reconnect',
    '/docs/api-reference/servers/reactivate',

    // Server Events
    '/docs/api-reference/servers/events',

    // Sites
    '/docs/api-reference/sites/list-sites',
    '/docs/api-reference/sites/create-site',
    '/docs/api-reference/sites/get-site',
    '/docs/api-reference/sites/update-site',
    '/docs/api-reference/sites/delete-site',
    '/docs/api-reference/sites/load-balancing',

    // Site Commands
    '/docs/api-reference/sites/commands/install-git-repository',
    '/docs/api-reference/sites/commands/update-git-repository',
    '/docs/api-reference/sites/commands/destroy-git-repository',

    // SSL Certificates
    '/docs/api-reference/certificates/list-certificates',
    '/docs/api-reference/certificates/create-certificate',
    '/docs/api-reference/certificates/get-certificate',
    '/docs/api-reference/certificates/install-certificate',
    '/docs/api-reference/certificates/activate-certificate',
    '/docs/api-reference/certificates/obtain-letsencrypt-certificate',
    '/docs/api-reference/certificates/delete-certificate',

    // Nginx
    '/docs/api-reference/nginx/get-nginx-configuration',
    '/docs/api-reference/nginx/update-nginx-configuration',

    // Environment
    '/docs/api-reference/environment/get-environment-file',
    '/docs/api-reference/environment/update-environment-file',

    // Deployments
    '/docs/api-reference/deployments/list-deployments',
    '/docs/api-reference/deployments/get-deployment',
    '/docs/api-reference/deployments/deploy-now',
    '/docs/api-reference/deployments/reset-deployment-state',
    '/docs/api-reference/deployments/get-deployment-log',
    '/docs/api-reference/deployments/get-deployment-script',
    '/docs/api-reference/deployments/update-deployment-script',
    '/docs/api-reference/deployments/enable-quick-deploy',
    '/docs/api-reference/deployments/disable-quick-deploy',

    // Workers
    '/docs/api-reference/workers/list-workers',
    '/docs/api-reference/workers/create-worker',
    '/docs/api-reference/workers/get-worker',
    '/docs/api-reference/workers/delete-worker',
    '/docs/api-reference/workers/restart-worker',

    // Daemons
    '/docs/api-reference/daemons/list-daemons',
    '/docs/api-reference/daemons/create-daemon',
    '/docs/api-reference/daemons/get-daemon',
    '/docs/api-reference/daemons/delete-daemon',
    '/docs/api-reference/daemons/restart-daemon',

    // Firewall Rules
    '/docs/api-reference/firewall/list-firewall-rules',
    '/docs/api-reference/firewall/create-firewall-rule',
    '/docs/api-reference/firewall/get-firewall-rule',
    '/docs/api-reference/firewall/delete-firewall-rule',

    // Scheduled Jobs
    '/docs/api-reference/jobs/list-scheduled-jobs',
    '/docs/api-reference/jobs/create-scheduled-job',
    '/docs/api-reference/jobs/get-scheduled-job',
    '/docs/api-reference/jobs/delete-scheduled-job',

    // Databases
    '/docs/api-reference/databases/list-databases',
    '/docs/api-reference/databases/create-database',
    '/docs/api-reference/databases/get-database',
    '/docs/api-reference/databases/update-database',
    '/docs/api-reference/databases/delete-database',
    '/docs/api-reference/databases/sync-databases',

    // Database Users
    '/docs/api-reference/database-users/list-database-users',
    '/docs/api-reference/database-users/create-database-user',
    '/docs/api-reference/database-users/get-database-user',
    '/docs/api-reference/database-users/update-database-user',
    '/docs/api-reference/database-users/delete-database-user',

    // Backups
    '/docs/api-reference/backups/list-backup-configurations',
    '/docs/api-reference/backups/create-backup-configuration',
    '/docs/api-reference/backups/get-backup-configuration',
    '/docs/api-reference/backups/delete-backup-configuration',
    '/docs/api-reference/backups/restore-backup',
    '/docs/api-reference/backups/delete-backup',

    // Recipes
    '/docs/api-reference/recipes/list-recipes',
    '/docs/api-reference/recipes/create-recipe',
    '/docs/api-reference/recipes/get-recipe',
    '/docs/api-reference/recipes/update-recipe',
    '/docs/api-reference/recipes/delete-recipe',
    '/docs/api-reference/recipes/run-recipe',

    // Credentials
    '/docs/api-reference/credentials/list-credentials',
    '/docs/api-reference/credentials/create-credential',
    '/docs/api-reference/credentials/get-credential',
    '/docs/api-reference/credentials/delete-credential',

    // PHP
    '/docs/api-reference/php/list-php-versions',
    '/docs/api-reference/php/install-php-version',
    '/docs/api-reference/php/update-default-php-version',

    // Redis
    '/docs/api-reference/redis/install-redis',
    '/docs/api-reference/redis/uninstall-redis',

    // Monitoring
    '/docs/api-reference/monitoring/enable-monitoring',
    '/docs/api-reference/monitoring/disable-monitoring',
];

// Create output directory if it doesn't exist
if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
}

/**
 * Fetch URL content
 */
function fetchUrl(url) {
    return new Promise((resolve, reject) => {
        const parsedUrl = new URL(url);
        const protocol = parsedUrl.protocol === 'https:' ? https : http;

        protocol.get(url, (res) => {
            let data = '';

            res.on('data', (chunk) => {
                data += chunk;
            });

            res.on('end', () => {
                if (res.statusCode >= 200 && res.statusCode < 300) {
                    resolve(data);
                } else if (res.statusCode === 404) {
                    resolve(null); // Page doesn't exist
                } else {
                    reject(new Error(`HTTP ${res.statusCode} for ${url}`));
                }
            });
        }).on('error', (err) => {
            reject(err);
        });
    });
}

/**
 * Extract main content from HTML page
 */
function extractContent(html, url) {
    let content = html;

    // Remove script tags
    content = content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');

    // Remove style tags
    content = content.replace(/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi, '');

    // Try to extract main content
    const mainContentMatch = content.match(/<main[^>]*>([\s\S]*)<\/main>/i);
    if (mainContentMatch) {
        content = mainContentMatch[1];
    } else {
        const articleMatch = content.match(/<article[^>]*>([\s\S]*)<\/article>/i);
        if (articleMatch) {
            content = articleMatch[1];
        }
    }

    // Add source URL at the top
    content = `<!-- Source: ${url} -->\n<!-- Fetched: ${new Date().toISOString()} -->\n\n${content}`;

    return content;
}

/**
 * Generate filename from URL path
 */
function urlToFilename(urlPath) {
    const filename = urlPath
        .replace('/docs/api-reference/', '')
        .replace(/\//g, '-') || 'introduction';
    return `${filename}.html`;
}

/**
 * Main execution
 */
async function main() {
    console.log('Fetching Laravel Forge API documentation...\n');
    console.log(`Total pages to fetch: ${API_PAGES.length}\n`);

    const index = [];
    let successCount = 0;
    let notFoundCount = 0;
    let errorCount = 0;

    for (const urlPath of API_PAGES) {
        const fullUrl = `${BASE_URL}${urlPath}`;
        const filename = urlToFilename(urlPath);
        const filepath = path.join(OUTPUT_DIR, filename);

        console.log(`[${successCount + notFoundCount + errorCount + 1}/${API_PAGES.length}] ${urlPath}`);

        try {
            const html = await fetchUrl(fullUrl);

            if (html === null) {
                console.log('  ⚠ Not found (404)\n');
                notFoundCount++;
                continue;
            }

            const content = extractContent(html, fullUrl);
            fs.writeFileSync(filepath, content, 'utf8');

            index.push({
                url: fullUrl,
                path: urlPath,
                filename: filename
            });

            console.log('  ✓ Saved\n');
            successCount++;

            // Be nice to the server
            await new Promise(resolve => setTimeout(resolve, 300));

        } catch (err) {
            console.error(`  ✗ Failed: ${err.message}\n`);
            errorCount++;
        }
    }

    // Save index file
    const indexPath = path.join(OUTPUT_DIR, 'index.json');
    fs.writeFileSync(indexPath, JSON.stringify(index, null, 2), 'utf8');

    // Create a README
    const readmePath = path.join(OUTPUT_DIR, 'README.md');
    const readmeContent = `# Laravel Forge API Documentation

This folder contains a local copy of the Laravel Forge API documentation fetched from ${BASE_URL}.

**Fetched:** ${new Date().toISOString()}
**Total Pages:** ${successCount}

## Pages by Category

${generateCategoryIndex(index)}

## Usage

When implementing the Forge SDK, always refer to these local documentation files rather than relying on:
- Existing knowledge that may be outdated
- Web searches that may return old API versions
- Cached/remembered information about the Forge API

The documentation in this folder represents the current, authoritative API specification.

## Fetch Results

- ✓ Successfully fetched: ${successCount} pages
- ⚠ Not found: ${notFoundCount} pages
- ✗ Errors: ${errorCount} pages
`;

    fs.writeFileSync(readmePath, readmeContent, 'utf8');

    console.log('\n' + '='.repeat(60));
    console.log('Summary:');
    console.log(`  ✓ Successfully fetched: ${successCount} pages`);
    console.log(`  ⚠ Not found: ${notFoundCount} pages`);
    console.log(`  ✗ Errors: ${errorCount} pages`);
    console.log('='.repeat(60) + '\n');
}

/**
 * Generate categorized index for README
 */
function generateCategoryIndex(index) {
    const categories = {};

    index.forEach(item => {
        const parts = item.path.split('/');
        const category = parts[3] || 'general';

        if (!categories[category]) {
            categories[category] = [];
        }

        categories[category].push(item);
    });

    let output = '';
    for (const [category, items] of Object.entries(categories).sort()) {
        output += `\n### ${category.charAt(0).toUpperCase() + category.slice(1)}\n\n`;
        items.forEach(item => {
            output += `- [${item.filename}](${item.filename}) - ${item.path}\n`;
        });
    }

    return output;
}

main();
