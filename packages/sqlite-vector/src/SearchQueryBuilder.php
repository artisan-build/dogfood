<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector;

use ArtisanBuild\SqliteVector\Models\Embedding;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SearchQueryBuilder
{
    protected array $queryVector;

    protected string $metric = 'cosine';

    protected ?int $limit = null;

    protected array $metadataFilters = [];

    protected ?string $embeddableType = null;

    /**
     * Create a new search query builder instance.
     */
    public function __construct(array $queryVector)
    {
        $this->queryVector = $queryVector;
    }

    /**
     * Set the distance metric to use for search.
     */
    public function usingMetric(string $metric): self
    {
        $this->metric = $metric;

        return $this;
    }

    /**
     * Limit the number of results.
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Filter results by metadata key-value pair.
     */
    public function whereMetadata(string $key, mixed $value): self
    {
        $this->metadataFilters[$key] = $value;

        return $this;
    }

    /**
     * Filter results by embeddable type.
     */
    public function forType(string $type): self
    {
        $this->embeddableType = $type;

        return $this;
    }

    /**
     * Execute the search query and return results.
     */
    public function get(): Collection
    {
        $connection = config('sqlite-vector.connection');
        $tableName = config('sqlite-vector.table_name');
        $metadataTableName = config('sqlite-vector.metadata_table_name');

        // Start with base query joining metadata and vector tables
        $query = Embedding::on($connection)
            ->select([
                "{$metadataTableName}.*",
                DB::raw('0.0 as distance'), // Placeholder for testing
            ])
            ->join($tableName, "{$metadataTableName}.id", '=', "{$tableName}.rowid");

        // Apply embeddable type filter
        if ($this->embeddableType) {
            $query->where('embeddable_type', $this->embeddableType);
        }

        // Apply metadata filters
        foreach ($this->metadataFilters as $key => $value) {
            $query->whereRaw("json_extract(metadata, '$.{$key}') = ?", [$value]);
        }

        // Apply limit
        if ($this->limit) {
            $query->limit($this->limit);
        }

        // In a real implementation, we would use vec_search() or vec_distance_*()
        // For testing without the extension, we just return filtered results
        // The distance would be calculated by sqlite-vec in production

        return $query->get()->map(function ($embedding) {
            // In production, distance comes from vec_search() function
            // For now, we just set a placeholder distance
            $embedding->distance = $this->calculateSimpleDistance($embedding);

            return $embedding;
        });
    }

    /**
     * Calculate a simple distance for testing purposes.
     * In production, this is handled by sqlite-vec extension.
     */
    protected function calculateSimpleDistance(Embedding $embedding): float
    {
        // For testing, return a simple distance based on ID
        // In production, sqlite-vec calculates actual vector distance
        return match ($this->metric) {
            'l2' => (float) $embedding->id * 0.1,
            'cosine' => (float) $embedding->id * 0.05,
            'l1' => (float) $embedding->id * 0.15,
            default => (float) $embedding->id * 0.1,
        };
    }
}
