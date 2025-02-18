<?php

declare(strict_types=1);

use ArtisanBuild\Adverbs\Attributes\Idempotent;
use Thunk\Verbs\Attributes\Hooks\Once;
use Thunk\Verbs\Event;

it('checks if classes extending Event with #[Inert] have #[Once] on handle', function (): void {

    collect(get_declared_classes())->filter(function ($class) {
        $reflection = ArtisanBuild\Mirror\Mirror::reflect($class)
            ->reflection_class;

        return $reflection->isSubclassOf(Event::class)
            && $reflection->hasMethod('handle');

    })->each(function ($class): void {
        $reflectionClass = new ReflectionClass($class);

        $method = $reflectionClass->getMethod('handle');

        if (empty($method->getAttributes(Once::class)) && empty($method->getAttributes(Idempotent::class))) {
            test()->fail("{$class} does not have #[Once] or #[Idempotent] on the handle method");
        }

        expect(true)->toBeTrue();

    });
    expect(true)->toBeTrue();
});
