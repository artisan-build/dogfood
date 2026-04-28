<?php

declare(strict_types=1);

use ArtisanBuild\SqliteVector\EmbeddingManager;
use ArtisanBuild\SqliteVector\Models\Embedding;
use Illuminate\Database\Eloquent\Model;
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
    // (virtual tables require the extension which may not be loaded in tests)
    $tableName = config('sqlite-vector.table_name');
    DB::connection($connection)->statement(
        "CREATE TABLE IF NOT EXISTS {$tableName} (rowid INTEGER PRIMARY KEY, embedding TEXT)"
    );
});

test('embedding manager stores embedding with synchronized IDs', function (): void {
    $manager = new EmbeddingManager;
    $article = makeTestModel();

    $vector = array_fill(0, 1536, 0.1);

    $embedding = $manager->store($article, $vector, ['source' => 'test']);

    expect($embedding)->toBeInstanceOf(Embedding::class)
        ->and($embedding->embeddable_type)->toBe($article::class)
        ->and($embedding->embeddable_id)->toBe(1)
        ->and($embedding->metadata)->toBe(['source' => 'test']);

    // Verify vector was stored in virtual table with matching ID
    $result = DB::connection(config('sqlite-vector.connection'))
        ->selectOne('SELECT rowid FROM '.config('sqlite-vector.table_name').' WHERE rowid = ?', [$embedding->id]);

    expect($result)->not->toBeNull()
        ->and($result->rowid)->toBe($embedding->id);
});

test('embedding manager validates vector dimensions', function (): void {
    $manager = new EmbeddingManager;
    $article = makeTestModel();

    $invalidVector = array_fill(0, 100, 0.1); // Wrong dimensions

    $manager->store($article, $invalidVector);
})->throws(InvalidArgumentException::class, 'Vector dimensions (100) do not match configured dimensions (1536)');

test('embedding manager updates existing embedding', function (): void {
    $manager = new EmbeddingManager;
    $article = makeTestModel();

    $vector1 = array_fill(0, 1536, 0.1);
    $embedding = $manager->store($article, $vector1);
    $originalId = $embedding->id;

    $vector2 = array_fill(0, 1536, 0.2);
    $updated = $manager->update($article, $vector2, ['source' => 'updated']);

    expect($updated->id)->toBe($originalId)
        ->and($updated->metadata)->toBe(['source' => 'updated'])
        ->and(Embedding::count())->toBe(1);
});

test('embedding manager deletes embeddings for model', function (): void {
    $manager = new EmbeddingManager;
    $article = makeTestModel();

    $vector = array_fill(0, 1536, 0.1);
    $embedding = $manager->store($article, $vector);

    expect(Embedding::count())->toBe(1);

    $manager->deleteForModel($article);

    expect(Embedding::count())->toBe(0);

    // Verify vector was deleted from virtual table
    $result = DB::connection(config('sqlite-vector.connection'))
        ->selectOne('SELECT rowid FROM '.config('sqlite-vector.table_name').' WHERE rowid = ?', [$embedding->id]);

    expect($result)->toBeNull();
});

test('embedding manager retrieves embeddings for model', function (): void {
    $manager = new EmbeddingManager;
    $article = makeTestModel();

    $vector1 = array_fill(0, 1536, 0.1);
    $vector2 = array_fill(0, 1536, 0.2);

    $manager->store($article, $vector1, ['chunk' => 1]);
    $manager->store($article, $vector2, ['chunk' => 2]);

    $embeddings = $manager->getForModel($article);

    expect($embeddings)->toHaveCount(2)
        ->and($embeddings->first()->embeddable_id)->toBe(1)
        ->and($embeddings->last()->embeddable_id)->toBe(1);
});

test('embedding manager stores batch embeddings', function (): void {
    $manager = new EmbeddingManager;
    $article = makeTestModel();

    $vectors = [
        array_fill(0, 1536, 0.1),
        array_fill(0, 1536, 0.2),
        array_fill(0, 1536, 0.3),
    ];

    $metadata = [
        ['chunk' => 1],
        ['chunk' => 2],
        ['chunk' => 3],
    ];

    $embeddings = $manager->storeBatch($article, $vectors, $metadata);

    expect($embeddings)->toHaveCount(3)
        ->and(Embedding::count())->toBe(3);
});

test('embedding manager handles custom dimensions', function (): void {
    config()->set('sqlite-vector.default_dimensions', 768);

    // Recreate table with new dimensions
    $tableName = config('sqlite-vector.table_name');
    $connection = config('sqlite-vector.connection');

    DB::connection($connection)->statement("DROP TABLE IF EXISTS {$tableName}");
    DB::connection($connection)->statement(
        "CREATE TABLE {$tableName} (rowid INTEGER PRIMARY KEY, embedding TEXT)"
    );

    $manager = new EmbeddingManager;
    $article = makeTestModel();

    $vector = array_fill(0, 768, 0.1);
    $embedding = $manager->store($article, $vector);

    expect($embedding)->toBeInstanceOf(Embedding::class);
});

test('embedding manager accepts model name as string', function (): void {
    $manager = new EmbeddingManager;
    $article = makeTestModel();
    $vector = array_fill(0, 1536, 0.1);

    $embedding = $manager->store(
        morphable: $article,
        vector: $vector,
        metadata: ['source' => 'test'],
        modelName: 'gpt-4o-mini'
    );

    expect($embedding->model)->toBe('gpt-4o-mini');
});
