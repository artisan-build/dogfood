<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
     * The attributes that should be cast.
     */
    protected $casts = [
        'metadata' => 'array',
        'embedded_at' => 'datetime',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return config('sqlite-vector.metadata_table_name', 'embedding_metadata');
    }

    /**
     * Get the current connection name for the model.
     */
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
}
