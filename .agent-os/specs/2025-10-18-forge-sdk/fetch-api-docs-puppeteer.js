#!/usr/bin/env node

/**
 * Fetch Laravel Forge API documentation using Puppeteer
 *
 * This script uses a headless browser to:
 * 1. Load the API reference page
 * 2. Extract all navigation links from the dynamically-loaded sidebar
 * 3. Visit each page and save the content
 */

import puppeteer from 'puppeteer';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const BASE_URL = 'https://forge.laravel.com';
const DOCS_START_URL = 'https://forge.laravel.com/docs/api-reference/introduction';
const OUTPUT_DIR = path.join(__dirname, 'api-docs');

// Create output directory if it doesn't exist
if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
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
    console.log('Launching browser...\n');

    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    const page = await browser.newPage();

    try {
        console.log('Loading documentation page...\n');
        await page.goto(DOCS_START_URL, {
            waitUntil: 'networkidle2',
            timeout: 30000
        });

        // Wait for navigation to be rendered (try multiple selectors)
        console.log('Waiting for navigation to load...\n');
        try {
            await page.waitForSelector('a[href*="api-reference"]', { timeout: 10000 });
        } catch (e) {
            console.log('Navigation selector not found, waiting for page load...\n');
        }

        // Give the page a moment to fully render
        await new Promise(resolve => setTimeout(resolve, 3000));

        // Try to expand all navigation sections
        console.log('Expanding navigation sections...\n');
        await page.evaluate(() => {
            // Try to click any expand/collapse buttons in the navigation
            const expandButtons = document.querySelectorAll('button[aria-expanded="false"]');
            expandButtons.forEach(button => button.click());

            // Also try clicking elements that might expand sections
            const navItems = document.querySelectorAll('nav button, nav [role="button"]');
            navItems.forEach(item => {
                try {
                    item.click();
                } catch (e) {
                    // Ignore click errors
                }
            });
        });

        // Wait for expanded content to render
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Take a screenshot for debugging
        await page.screenshot({ path: path.join(OUTPUT_DIR, 'debug-screenshot.png'), fullPage: true });
        console.log('Screenshot saved to api-docs/debug-screenshot.png\n');

        // Extract all API reference links from the page
        console.log('Extracting navigation links...\n');
        const links = await page.evaluate(() => {
            // Find all links that contain api-reference
            const allLinks = Array.from(document.querySelectorAll('a[href*="api-reference"]'));

            // Debug: log what we found
            console.log('Total links found:', allLinks.length);
            console.log('Sample links:', allLinks.slice(0, 10).map(l => l.href));

            return allLinks
                .map(link => {
                    const href = link.getAttribute('href');
                    // Handle both relative and absolute URLs
                    if (href.startsWith('/docs/')) {
                        return href;
                    } else if (href.startsWith('/api-reference/')) {
                        return '/docs' + href;
                    } else if (href.includes('forge.laravel.com/docs/')) {
                        return href.split('forge.laravel.com')[1];
                    }
                    return null;
                })
                .filter(href => href !== null)
                .filter((href, index, self) => self.indexOf(href) === index); // Remove duplicates
        });

        console.log(`Found ${links.length} documentation pages\n`);

        const index = [];
        let successCount = 0;
        let errorCount = 0;

        // Process each link
        for (let i = 0; i < links.length; i++) {
            const urlPath = links[i];
            const fullUrl = urlPath.startsWith('http') ? urlPath : `${BASE_URL}${urlPath}`;
            const filename = urlToFilename(urlPath);
            const filepath = path.join(OUTPUT_DIR, filename);

            console.log(`[${i + 1}/${links.length}] ${urlPath}`);

            try {
                // Navigate to the page
                await page.goto(fullUrl, {
                    waitUntil: 'networkidle2',
                    timeout: 30000
                });

                // Wait for main content to load
                await page.waitForSelector('main, article', { timeout: 5000 }).catch(() => {});

                // Give dynamic content time to render
                await new Promise(resolve => setTimeout(resolve, 1000));

                // Extract the main content
                const content = await page.evaluate((url) => {
                    // Try to get just the main content area
                    const main = document.querySelector('main');
                    const article = document.querySelector('article');
                    const contentArea = main || article || document.body;

                    // Remove scripts and styles
                    const scripts = contentArea.querySelectorAll('script');
                    const styles = contentArea.querySelectorAll('style');
                    scripts.forEach(s => s.remove());
                    styles.forEach(s => s.remove());

                    const html = contentArea.innerHTML;

                    return `<!-- Source: ${url} -->
<!-- Fetched: ${new Date().toISOString()} -->

${html}`;
                }, fullUrl);

                // Save the content
                fs.writeFileSync(filepath, content, 'utf8');

                index.push({
                    url: fullUrl,
                    path: urlPath,
                    filename: filename
                });

                console.log('  ✓ Saved\n');
                successCount++;

                // Be nice to the server
                await new Promise(resolve => setTimeout(resolve, 500));

            } catch (err) {
                console.error(`  ✗ Failed: ${err.message}\n`);
                errorCount++;
            }
        }

        // Save index file
        const indexPath = path.join(OUTPUT_DIR, 'index.json');
        fs.writeFileSync(indexPath, JSON.stringify(index, null, 2), 'utf8');

        // Generate categorized index
        const categories = {};
        index.forEach(item => {
            const parts = item.path.split('/');
            const category = parts[3] || 'general';

            if (!categories[category]) {
                categories[category] = [];
            }

            categories[category].push(item);
        });

        let categoryIndex = '';
        for (const [category, items] of Object.entries(categories).sort()) {
            categoryIndex += `\n### ${category.charAt(0).toUpperCase() + category.slice(1)}\n\n`;
            items.forEach(item => {
                const pageName = item.path.split('/').pop() || 'introduction';
                categoryIndex += `- [${pageName}](${item.filename})\n`;
            });
        }

        // Create a README
        const readmePath = path.join(OUTPUT_DIR, 'README.md');
        const readmeContent = `# Laravel Forge API Documentation

This folder contains a local copy of the Laravel Forge API documentation fetched from ${BASE_URL}.

**Fetched:** ${new Date().toISOString()}
**Total Pages:** ${successCount}

## Pages by Category
${categoryIndex}

## Usage

When implementing the Forge SDK, **always refer to these local documentation files** rather than relying on:
- Existing knowledge that may be outdated
- Web searches that may return old API versions
- Cached/remembered information about the Forge API

The documentation in this folder represents the current, authoritative API specification.

## Fetch Results

- ✓ Successfully fetched: ${successCount} pages
- ✗ Errors: ${errorCount} pages
`;

        fs.writeFileSync(readmePath, readmeContent, 'utf8');

        console.log('\n' + '='.repeat(60));
        console.log('Summary:');
        console.log(`  ✓ Successfully fetched: ${successCount} pages`);
        console.log(`  ✗ Errors: ${errorCount} pages`);
        console.log('='.repeat(60) + '\n');

    } catch (err) {
        console.error('Error:', err.message);
        process.exit(1);
    } finally {
        await browser.close();
    }
}

main();
