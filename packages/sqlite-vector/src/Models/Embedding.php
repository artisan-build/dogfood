<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Override;

/**
 * Stores embedding metadata linked to a morphable model.
 *
 * @property int $id
 * @property string $embeddable_type
 * @property int $embeddable_id
 * @property array|null $metadata
 * @property string|null $source
 * @property string|null $model
 * @property Carbon|null $embedded_at
 * @property float|null $distance
 */
class Embedding extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'embeddable_type',
        'embeddable_id',
        'metadata',
        'source',
        'model',
        'embedded_at',
    ];

    /**
     * Get the table associated with the model.
     */
    #[Override]
    public function getTable(): string
    {
        return config('sqlite-vector.metadata_table_name', 'embedding_metadata');
    }

    /**
     * Get the current connection name for the model.
     */
    #[Override]
    public function getConnectionName(): ?string
    {
        return config('sqlite-vector.connection', 'sqlite');
    }

    /**
     * Get the embeddable model that the embedding belongs to.
     */
    public function embeddable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The attributes that should be cast.
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'embedded_at' => 'datetime',
        ];
    }
}
