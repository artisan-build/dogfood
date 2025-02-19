<?php

declare(strict_types=1);

use ArtisanBuild\Adverbs\Attributes\Inert;
use Thunk\Verbs\Event;

it('checks if classes extending Event with #[Inert] have empty states', function (): void {
    $events = collect(get_declared_classes())->filter(function ($class) {
        $reflectionClass = new ReflectionClass($class);

        return $reflectionClass->isSubclassOf(Event::class)
            && ! empty($reflectionClass->getAttributes(Inert::class));
    });

    $events->each(function ($class): void {
        if (! $class::fire()->states()->isEmpty()) {
            test()->fail("{$class} is marked as inert but it interacts with states.");
        }
    });
    expect(true)->toBeTrue();
});
