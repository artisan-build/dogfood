<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \ArtisanBuild\SqliteVector\Models\Embedding store(\Illuminate\Database\Eloquent\Model $morphable, array $vector, array $metadata = [], ?string $source = null, ?string $modelName = null)
 * @method static \ArtisanBuild\SqliteVector\Models\Embedding update(\Illuminate\Database\Eloquent\Model $morphable, array $vector, array $metadata = [], ?string $source = null, ?string $modelName = null)
 * @method static int deleteForModel(\Illuminate\Database\Eloquent\Model $morphable)
 * @method static \Illuminate\Database\Eloquent\Collection getForModel(\Illuminate\Database\Eloquent\Model $morphable)
 * @method static \Illuminate\Database\Eloquent\Collection storeBatch(\Illuminate\Database\Eloquent\Model $morphable, array $vectors, array $metadata = [], ?string $source = null, ?string $modelName = null)
 *
 * @see \ArtisanBuild\SqliteVector\EmbeddingManager
 */
class Embedding extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'sqlite-vector.manager';
    }
}
