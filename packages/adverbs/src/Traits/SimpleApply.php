<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Traits;

use ReflectionClass;
use ReflectionProperty;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

trait SimpleApply
{
    public function apply(): void
    {
        // This method can only work if the event only interacts with a single state.
        $state = $this->states()->sole();

        $reflection = new ReflectionClass($this);

        collect($reflection->getProperties())
            ->filter(fn (ReflectionProperty $property) => $property->getName() !== 'id'
                && blank($property->getAttributes(StateId::class)))
            ->each(function ($property) use ($state): void {
                $property_name = $property->getName();
                if (property_exists($state, $property_name) && $this->{$property_name} !== null) {
                    $state->{$property_name} = $this->{$property_name};
                }
            });
    }
}
