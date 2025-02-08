<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Traits;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionProperty;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Attributes\Hooks\Once;

trait ReturnsModelInstanceOnHandle
{
    #[Once]
    final public function handle(): Model
    {
        $reflection = new ReflectionClass($this);

        $id_property = collect($reflection->getProperties(ReflectionProperty::IS_PUBLIC))
            /** @phpstan-ignore-next-line  */
            ->filter(fn ($property) => $property->getAttributes(StateId::class))
            ->first()->getName();

        $model = new class extends Model
        {
            protected $guarded = [];
        };

        return new $model(['id' => $this->{$id_property}]);
    }
}
