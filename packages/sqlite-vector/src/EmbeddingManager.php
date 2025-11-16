<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector;

use ArtisanBuild\SqliteVector\Models\Embedding;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmbeddingManager
{
    /**
     * Store an embedding for a model.
     */
    public function store(
        Model $morphable,
        array $vector,
        array $metadata = [],
        ?string $source = null,
        ?string $modelName = null
    ): Embedding {
        $this->validateVectorDimensions($vector);

        $connection = config('sqlite-vector.connection');

        return DB::connection($connection)->transaction(function () use ($morphable, $vector, $metadata, $source, $modelName) {
            // Create metadata record first to get ID
            $embedding = new Embedding;
            $embedding->embeddable_type = get_class($morphable);
            $embedding->embeddable_id = $morphable->id;
            $embedding->metadata = $metadata;
            $embedding->source = $source;
            $embedding->model = $modelName;
            $embedding->embedded_at = now();
            $embedding->save();

            // Insert vector into virtual table with matching rowid
            $this->insertVector($embedding->id, $vector);

            return $embedding->fresh();
        });
    }

    /**
     * Update an existing embedding for a model.
     */
    public function update(
        Model $morphable,
        array $vector,
        array $metadata = [],
        ?string $source = null,
        ?string $modelName = null
    ): Embedding {
        $this->validateVectorDimensions($vector);

        $connection = config('sqlite-vector.connection');

        return DB::connection($connection)->transaction(function () use ($morphable, $vector, $metadata, $source, $modelName) {
            $embedding = Embedding::where('embeddable_type', get_class($morphable))
                ->where('embeddable_id', $morphable->id)
                ->firstOrFail();

            $embedding->metadata = $metadata;
            $embedding->source = $source;
            $embedding->model = $modelName;
            $embedding->embedded_at = now();
            $embedding->save();

            // Update vector in virtual table
            $this->updateVector($embedding->id, $vector);

            return $embedding->fresh();
        });
    }

    /**
     * Delete all embeddings for a model.
     */
    public function deleteForModel(Model $morphable): int
    {
        $connection = config('sqlite-vector.connection');

        return DB::connection($connection)->transaction(function () use ($morphable) {
            $embeddings = Embedding::where('embeddable_type', get_class($morphable))
                ->where('embeddable_id', $morphable->id)
                ->get();

            foreach ($embeddings as $embedding) {
                $this->deleteVector($embedding->id);
            }

            return Embedding::where('embeddable_type', get_class($morphable))
                ->where('embeddable_id', $morphable->id)
                ->delete();
        });
    }

    /**
     * Get all embeddings for a model.
     */
    public function getForModel(Model $morphable): Collection
    {
        return Embedding::where('embeddable_type', get_class($morphable))
            ->where('embeddable_id', $morphable->id)
            ->get();
    }

    /**
     * Store multiple embeddings for a model in a single transaction.
     */
    public function storeBatch(
        Model $morphable,
        array $vectors,
        array $metadata = [],
        ?string $source = null,
        ?string $modelName = null
    ): Collection {
        foreach ($vectors as $vector) {
            $this->validateVectorDimensions($vector);
        }

        $connection = config('sqlite-vector.connection');

        return DB::connection($connection)->transaction(function () use ($morphable, $vectors, $metadata, $source, $modelName) {
            $embeddings = new Collection;

            foreach ($vectors as $index => $vector) {
                $embedding = new Embedding;
                $embedding->embeddable_type = get_class($morphable);
                $embedding->embeddable_id = $morphable->id;
                $embedding->metadata = $metadata[$index] ?? [];
                $embedding->source = $source;
                $embedding->model = $modelName;
                $embedding->embedded_at = now();
                $embedding->save();

                $this->insertVector($embedding->id, $vector);

                $embeddings->push($embedding->fresh());
            }

            return $embeddings;
        });
    }

    /**
     * Validate that vector dimensions match configured dimensions.
     */
    protected function validateVectorDimensions(array $vector): void
    {
        $expectedDimensions = config('sqlite-vector.default_dimensions');
        $actualDimensions = count($vector);

        if ($actualDimensions !== $expectedDimensions) {
            throw new \InvalidArgumentException(
                "Vector dimensions ({$actualDimensions}) do not match configured dimensions ({$expectedDimensions})"
            );
        }
    }

    /**
     * Insert a vector into the virtual table with a specific rowid.
     */
    protected function insertVector(int $id, array $vector): void
    {
        $connection = config('sqlite-vector.connection');
        $tableName = config('sqlite-vector.table_name');

        $vectorJson = json_encode($vector);

        DB::connection($connection)->statement(
            "INSERT INTO {$tableName} (rowid, embedding) VALUES (?, ?)",
            [$id, $vectorJson]
        );
    }

    /**
     * Update a vector in the virtual table.
     */
    protected function updateVector(int $id, array $vector): void
    {
        $connection = config('sqlite-vector.connection');
        $tableName = config('sqlite-vector.table_name');

        $vectorJson = json_encode($vector);

        DB::connection($connection)->statement(
            "UPDATE {$tableName} SET embedding = ? WHERE rowid = ?",
            [$vectorJson, $id]
        );
    }

    /**
     * Delete a vector from the virtual table.
     */
    protected function deleteVector(int $id): void
    {
        $connection = config('sqlite-vector.connection');
        $tableName = config('sqlite-vector.table_name');

        DB::connection($connection)->statement(
            "DELETE FROM {$tableName} WHERE rowid = ?",
            [$id]
        );
    }
}
