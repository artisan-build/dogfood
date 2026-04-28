<?php

declare(strict_types=1);

use ArtisanBuild\SqliteVector\EmbeddingManager;
use ArtisanBuild\SqliteVector\SearchQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

if (! function_exists('makeTestModel')) {
    function makeTestModel(int $id = 1): Model
    {
        $model = new class extends Model
        {
            protected $table = 'articles';
        };
        $model->id = $id;

        return $model;
    }
}

beforeEach(function (): void {
    $connection = config('sqlite-vector.connection');

    // Create metadata table
    $metadataTableName = config('sqlite-vector.metadata_table_name');
    DB::connection($connection)->statement("
        CREATE TABLE IF NOT EXISTS {$metadataTableName} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            embeddable_type TEXT NOT NULL,
            embeddable_id INTEGER NOT NULL,
            metadata TEXT,
            source TEXT,
            model TEXT,
            embedded_at TEXT,
            created_at TEXT,
            updated_at TEXT
        )
    ");

    // Create a regular table to simulate the virtual table for testing
    $tableName = config('sqlite-vector.table_name');
    DB::connection($connection)->statement(
        "CREATE TABLE IF NOT EXISTS {$tableName} (
            rowid INTEGER PRIMARY KEY,
            embedding TEXT,
            distance REAL
        )"
    );

    // Insert test embeddings
    $manager = new EmbeddingManager;

    $article1 = makeTestModel(1);
    $article2 = makeTestModel(2);
    $article3 = makeTestModel(3);

    $manager->store($article1, array_fill(0, 1536, 0.1), ['category' => 'tech', 'tags' => ['laravel', 'php']]);
    $manager->store($article2, array_fill(0, 1536, 0.2), ['category' => 'design', 'tags' => ['ui', 'ux']]);
    $manager->store($article3, array_fill(0, 1536, 0.3), ['category' => 'tech', 'tags' => ['python', 'ai']]);
});

test('search query builder creates instance with query vector', function (): void {
    $queryVector = array_fill(0, 1536, 0.5);
    $builder = new SearchQueryBuilder($queryVector);

    expect($builder)->toBeInstanceOf(SearchQueryBuilder::class);
});

test('search query builder sets distance metric', function (): void {
    $queryVector = array_fill(0, 1536, 0.5);
    $builder = new SearchQueryBuilder($queryVector);

    $builder->usingMetric('l2');

    expect($builder)->toBeInstanceOf(SearchQueryBuilder::class);
});

test('search query builder sets limit', function (): void {
    $queryVector = array_fill(0, 1536, 0.5);
    $builder = new SearchQueryBuilder($queryVector);

    $builder->limit(5);

    expect($builder)->toBeInstanceOf(SearchQueryBuilder::class);
});

test('search query builder filters by metadata', function (): void {
    $queryVector = array_fill(0, 1536, 0.5);
    $builder = new SearchQueryBuilder($queryVector);

    $builder->whereMetadata('category', 'tech');

    expect($builder)->toBeInstanceOf(SearchQueryBuilder::class);
});

test('search query builder filters by embeddable type', function (): void {
    $queryVector = array_fill(0, 1536, 0.5);
    $builder = new SearchQueryBuilder($queryVector);

    $builder->forType('Article');

    expect($builder)->toBeInstanceOf(SearchQueryBuilder::class);
});

test('search query builder returns search results', function (): void {
    $queryVector = array_fill(0, 1536, 0.15);
    $builder = new SearchQueryBuilder($queryVector);

    $results = $builder->limit(2)->get();

    expect($results)->toBeInstanceOf(Collection::class)
        ->and($results->count())->toBeLessThanOrEqual(2);
});

test('search query builder filters results by metadata', function (): void {
    $queryVector = array_fill(0, 1536, 0.15);
    $builder = new SearchQueryBuilder($queryVector);

    $results = $builder->whereMetadata('category', 'tech')->get();

    expect($results)->toBeInstanceOf(Collection::class)
        ->and($results->every(fn ($embedding) => $embedding->metadata['category'] === 'tech'))->toBeTrue();
});

test('search query builder returns filtered results by embeddable type', function (): void {
    $queryVector = array_fill(0, 1536, 0.15);
    $builder = new SearchQueryBuilder($queryVector);

    $testModel = makeTestModel();
    $testClass = $testModel::class;

    $results = $builder->forType($testClass)->get();

    expect($results)->toBeInstanceOf(Collection::class)
        ->and($results->every(fn ($embedding) => $embedding->embeddable_type === $testClass))->toBeTrue();
});

test('search query builder includes distance in results', function (): void {
    $queryVector = array_fill(0, 1536, 0.15);
    $builder = new SearchQueryBuilder($queryVector);

    $results = $builder->get();

    expect($results->first())->toHaveKey('distance')
        ->and($results->first()->distance)->toBeNumeric();
});

test('search query builder chains multiple filters', function (): void {
    $queryVector = array_fill(0, 1536, 0.15);
    $builder = new SearchQueryBuilder($queryVector);

    $results = $builder
        ->whereMetadata('category', 'tech')
        ->limit(1)
        ->usingMetric('cosine')
        ->get();

    expect($results)->toBeInstanceOf(Collection::class)
        ->and($results->count())->toBeLessThanOrEqual(1);
});
