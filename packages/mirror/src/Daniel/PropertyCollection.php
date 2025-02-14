<?php

namespace ArtisanBuild\Mirror\Daniel;

use Illuminate\Support\Collection;

class PropertyCollection extends Collection
{
    final public function __construct(array $properties)
    {
        parent::__construct($properties);
    }

    public static function fromReflect(Reflect $reflect): static
    {
        $class = $reflect->reflector;

        return (new static($class->getProperties()))
            ->mapWithKeys(
                fn ($p) => [
                    $p->getName() => Property::fromReflectionProperty($p, $reflect->object),
                ]
            );
    }

    public function hasAttribute(string $type, ?array $arguments = null): static
    {

        return $this->filter(
            fn ($prop) => $prop
                ->attributes
                ->ofType($type)
                ->when(
                    $arguments,
                    fn ($a) => $a->withArguments($arguments)
                )
                ->isNotEmpty()
        );
    }

    public function initialized(): static
    {
        return $this->filter(fn ($p) => $p->initialized);
    }

    public function uninitialized(): static
    {
        return $this->reject(fn ($p): bool => (bool) $p->initialized);
    }

    public function public(): static
    {
        return $this->filter(fn ($p) => $p->visibility === PropertyVisibility::PUBLIC);
    }

    public function private(): static
    {
        return $this->filter(fn ($p) => $p->visibility === PropertyVisibility::PRIVATE);
    }

    public function protected(): static
    {
        return $this->filter(fn ($p) => $p->visibility === PropertyVisibility::PROTECTED);
    }

    public function ofType(string $type, bool $include_nullable = false): static
    {
        return $this->filter(
            fn ($p) => ($p->types->contains($type)) && ($include_nullable || ! $p->nullable)
        );
    }

    public function withValues(): Collection
    {
        return $this->map->value->toBase();
    }

    public function getValuesFromAttributeArgument(string $attribute, string $argument): Collection
    {
        return $this->map(fn ($prop) => $prop->attributes
            ->ofType($attribute)
            ->first()
            ?->args
            ?->get($argument))->toBase();
    }

    public function setKeysFromAttributeArgument(string $attribute, string $argument): static
    {
        return $this->mapWithKeys(function (Property $prop) use ($attribute, $argument) {
            $attr_value = $prop->getAttributeArgument($attribute, $argument);

            return [$attr_value => $prop];
        });
    }
}
