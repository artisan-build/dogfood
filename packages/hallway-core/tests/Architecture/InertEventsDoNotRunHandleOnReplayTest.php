<?php

declare(strict_types=1);

use ArtisanBuild\Adverbs\Attributes\Inert;
use Thunk\Verbs\Attributes\Hooks\Once;
use Thunk\Verbs\Event;

it('checks if classes extending Event with #[Inert] have #[Once] on handle', function (): void {

    collect(get_declared_classes())->filter(function ($class) {
        $reflectionClass = new ReflectionClass($class);

        return $reflectionClass->isSubclassOf(Event::class)
            && ! empty($reflectionClass->getAttributes(Inert::class));
    })->each(function ($class): void {
        $reflectionClass = new ReflectionClass($class);

        $method = $reflectionClass->getMethod('handle');

        if (empty($method->getAttributes(Once::class))) {
            test()->fail("{$class} is marked as inert but does not have #[Once] on the handle method");
        }

        expect(true)->toBeTrue();

    });
});
