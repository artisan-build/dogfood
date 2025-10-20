#!/usr/bin/env node

/**
 * Test script to verify markdown fetching works
 */

import puppeteer from 'puppeteer';

async function test() {
    console.log('Testing markdown fetch from Mintlify...\n');

    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    const page = await browser.newPage();

    try {
        const testUrl = 'https://forge.laravel.com/docs/api-reference/introduction.md';
        console.log(`Fetching: ${testUrl}\n`);

        await page.goto(testUrl, {
            waitUntil: 'networkidle2',
            timeout: 30000
        });

        await new Promise(resolve => setTimeout(resolve, 500));

        const content = await page.evaluate(() => {
            const pre = document.querySelector('pre');
            const body = document.body;

            if (pre) {
                return { source: 'pre', content: pre.textContent };
            } else {
                return { source: 'body', content: body.textContent };
            }
        });

        console.log(`Content source: ${content.source}`);
        console.log(`Content length: ${content.content.length} characters`);
        console.log('\nFirst 500 characters:');
        console.log('='.repeat(60));
        console.log(content.content.substring(0, 500));
        console.log('='.repeat(60));

        // Check if it looks like markdown
        const hasMarkdownSyntax = content.content.includes('#') || content.content.includes('```');
        console.log(`\nLooks like markdown: ${hasMarkdownSyntax ? 'YES ✓' : 'NO ✗'}`);

    } catch (err) {
        console.error('Error:', err.message);
    } finally {
        await browser.close();
    }
}

test();
