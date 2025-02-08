<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

pest()->extends(TestCase::class, LazilyRefreshDatabase::class)
    ->in('Feature', '../packages/*')
    ->beforeEach(fn () => $this->withoutVite());

expect()->extend('toBeIgnoringWhitespace', function (string $expected): void {
    expect(trim((string) preg_replace('/\s\s+/', ' ', (string) $this->value)))->toBe(trim((string) preg_replace('/\s\s+/', ' ', $expected)));
});
