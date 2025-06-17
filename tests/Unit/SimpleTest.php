<?php

it('can perform basic assertions', function (): void {
    expect(true)->toBeTrue();
    expect(1 + 1)->toBe(2);
    expect('hello world')->toContain('world');
});
