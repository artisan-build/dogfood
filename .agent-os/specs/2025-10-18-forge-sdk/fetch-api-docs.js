#!/usr/bin/env node

/**
 * Script to fetch Laravel Forge API documentation
 *
 * This script:
 * 1. Fetches the main API docs page
 * 2. Extracts all sidebar links
 * 3. Fetches each documentation page
 * 4. Saves the content to the api-docs folder
 */

import https from 'https';
import http from 'http';
import fs from 'fs';
import path from 'path';
import { URL, fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const BASE_URL = 'https://forge.laravel.com';
const DOCS_URL = 'https://forge.laravel.com/docs/api-reference/introduction';
const OUTPUT_DIR = path.join(__dirname, 'api-docs');

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
 * Extract sidebar links from HTML
 */
function extractSidebarLinks(html) {
    const links = new Set();

    // Look for links in the sidebar navigation
    // The pattern matches href="/docs/api-reference/..." links
    const linkPattern = /href="(\/docs\/api-reference\/[^"#]+)"/g;
    let match;

    while ((match = linkPattern.exec(html)) !== null) {
        const href = match[1].replace(/\/$/, ''); // Remove trailing slash
        links.add(href);
    }

    // Also look for links with full URLs
    const fullUrlPattern = /href="https:\/\/forge\.laravel\.com(\/docs\/api-reference\/[^"#]+)"/g;
    while ((match = fullUrlPattern.exec(html)) !== null) {
        const href = match[1].replace(/\/$/, '');
        links.add(href);
    }

    return Array.from(links).sort();
}

/**
 * Extract main content from HTML page
 */
function extractContent(html, url) {
    // Try to extract just the main content area
    // This is a simple extraction - we'll get the whole body but remove scripts/styles

    let content = html;

    // Remove script tags
    content = content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');

    // Remove style tags
    content = content.replace(/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi, '');

    // Try to extract main content if we can find it
    const mainContentMatch = content.match(/<main[^>]*>([\s\S]*)<\/main>/i);
    if (mainContentMatch) {
        content = mainContentMatch[1];
    } else {
        // Fallback: try to find article tag
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
 * Generate filename from URL
 */
function urlToFilename(url) {
    const urlPath = url.replace(BASE_URL, '').replace('/docs/api-reference/', '');
    const filename = urlPath.replace(/\//g, '-') || 'introduction';
    return `${filename}.html`;
}

/**
 * Main execution
 */
async function main() {
    console.log('Fetching Laravel Forge API documentation...\n');

    try {
        // Fetch the introduction page
        console.log(`Fetching: ${DOCS_URL}`);
        const introHtml = await fetchUrl(DOCS_URL);

        // Extract all sidebar links
        const links = extractSidebarLinks(introHtml);
        console.log(`Found ${links.length} documentation pages\n`);

        // Create an index of all pages
        const index = [];

        // Process each link
        for (const link of links) {
            const fullUrl = `${BASE_URL}${link}`;
            const filename = urlToFilename(link);
            const filepath = path.join(OUTPUT_DIR, filename);

            console.log(`Fetching: ${fullUrl}`);
            console.log(`Saving to: ${filename}`);

            try {
                const html = await fetchUrl(fullUrl);
                const content = extractContent(html, fullUrl);

                fs.writeFileSync(filepath, content, 'utf8');

                index.push({
                    url: fullUrl,
                    path: link,
                    filename: filename
                });

                console.log('✓ Saved\n');

                // Be nice to the server
                await new Promise(resolve => setTimeout(resolve, 500));

            } catch (err) {
                console.error(`✗ Failed: ${err.message}\n`);
            }
        }

        // Save index file
        const indexPath = path.join(OUTPUT_DIR, 'index.json');
        fs.writeFileSync(indexPath, JSON.stringify(index, null, 2), 'utf8');
        console.log(`\nIndex saved to: index.json`);

        // Create a README
        const readmePath = path.join(OUTPUT_DIR, 'README.md');
        const readmeContent = `# Laravel Forge API Documentation

This folder contains a local copy of the Laravel Forge API documentation fetched from ${BASE_URL}.

**Fetched:** ${new Date().toISOString()}

## Pages

${index.map(item => `- [${item.path}](${item.filename}) - ${item.url}`).join('\n')}

## Usage

When implementing the Forge SDK, always refer to these local documentation files rather than relying on:
- Existing knowledge that may be outdated
- Web searches that may return old API versions
- Cached/remembered information about the Forge API

The documentation in this folder represents the current, authoritative API specification.
`;

        fs.writeFileSync(readmePath, readmeContent, 'utf8');
        console.log(`README saved to: README.md`);

        console.log(`\n✓ Successfully fetched ${index.length} pages`);

    } catch (err) {
        console.error('Error:', err.message);
        process.exit(1);
    }
}

main();
