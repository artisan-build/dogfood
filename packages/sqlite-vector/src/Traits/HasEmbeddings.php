<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector\Traits;

use ArtisanBuild\SqliteVector\Facades\Embedding as EmbeddingFacade;
use ArtisanBuild\SqliteVector\Models\Embedding;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasEmbeddings
{
    /**
     * Get all embeddings for this model.
     */
    public function embeddings(): MorphMany
    {
        return $this->morphMany(Embedding::class, 'embeddable');
    }

    /**
     * Create a new embedding for this model.
     */
    public function embed(
        array $vector,
        array $metadata = [],
        ?string $source = null,
        ?string $modelName = null
    ): Embedding {
        return EmbeddingFacade::store($this, $vector, $metadata, $source, $modelName);
    }

    /**
     * Update the existing embedding for this model.
     */
    public function updateEmbedding(
        array $vector,
        array $metadata = [],
        ?string $source = null,
        ?string $modelName = null
    ): Embedding {
        return EmbeddingFacade::update($this, $vector, $metadata, $source, $modelName);
    }

    /**
     * Remove all embeddings for this model.
     */
    public function removeEmbeddings(): int
    {
        return EmbeddingFacade::deleteForModel($this);
    }

    /**
     * Get all embeddings for this model using the manager.
     */
    public function getEmbeddings(): Collection
    {
        return EmbeddingFacade::getForModel($this);
    }

    /**
     * Store multiple embeddings for this model.
     */
    public function embedBatch(
        array $vectors,
        array $metadata = [],
        ?string $source = null,
        ?string $modelName = null
    ): Collection {
        return EmbeddingFacade::storeBatch($this, $vectors, $metadata, $source, $modelName);
    }
}
