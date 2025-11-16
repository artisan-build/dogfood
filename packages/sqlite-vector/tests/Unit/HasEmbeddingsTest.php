<?php

declare(strict_types=1);

use ArtisanBuild\SqliteVector\Models\Embedding;
use ArtisanBuild\SqliteVector\Traits\HasEmbeddings;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
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
        "CREATE TABLE IF NOT EXISTS {$tableName} (rowid INTEGER PRIMARY KEY, embedding TEXT)"
    );

    // Create test model table
    DB::connection($connection)->statement('
        CREATE TABLE IF NOT EXISTS articles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT,
            content TEXT,
            created_at TEXT,
            updated_at TEXT
        )
    ');
});

test('model has embeddings relationship', function () {
    $article = new class extends Illuminate\Database\Eloquent\Model
    {
        use HasEmbeddings;

        protected $table = 'articles';
    };

    expect($article->embeddings())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphMany::class);
});

test('model can embed content', function () {
    $article = new class extends Illuminate\Database\Eloquent\Model
    {
        use HasEmbeddings;

        protected $table = 'articles';

        public function getConnectionName()
        {
            return config('sqlite-vector.connection');
        }
    };

    $article->title = 'Test Article';
    $article->content = 'Test content';
    $article->save();

    $vector = array_fill(0, 1536, 0.1);
    $embedding = $article->embed($vector, ['source' => 'test']);

    expect($embedding)->toBeInstanceOf(Embedding::class)
        ->and($embedding->embeddable_id)->toBe($article->id)
        ->and($embedding->embeddable_type)->toBe(get_class($article))
        ->and($embedding->metadata)->toBe(['source' => 'test']);
});

test('model can update embedding', function () {
    $article = new class extends Illuminate\Database\Eloquent\Model
    {
        use HasEmbeddings;

        protected $table = 'articles';

        public function getConnectionName()
        {
            return config('sqlite-vector.connection');
        }
    };

    $article->title = 'Test Article';
    $article->save();

    $vector1 = array_fill(0, 1536, 0.1);
    $embedding1 = $article->embed($vector1);

    $vector2 = array_fill(0, 1536, 0.2);
    $embedding2 = $article->updateEmbedding($vector2, ['source' => 'updated']);

    expect($embedding2->id)->toBe($embedding1->id)
        ->and($embedding2->metadata)->toBe(['source' => 'updated']);
});

test('model can remove embeddings', function () {
    $article = new class extends Illuminate\Database\Eloquent\Model
    {
        use HasEmbeddings;

        protected $table = 'articles';

        public function getConnectionName()
        {
            return config('sqlite-vector.connection');
        }
    };

    $article->title = 'Test Article';
    $article->save();

    $vector = array_fill(0, 1536, 0.1);
    $article->embed($vector);

    expect(Embedding::count())->toBe(1);

    $article->removeEmbeddings();

    expect(Embedding::count())->toBe(0);
});

test('model can embed batch content', function () {
    $article = new class extends Illuminate\Database\Eloquent\Model
    {
        use HasEmbeddings;

        protected $table = 'articles';

        public function getConnectionName()
        {
            return config('sqlite-vector.connection');
        }
    };

    $article->title = 'Test Article';
    $article->save();

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

    $embeddings = $article->embedBatch($vectors, $metadata);

    expect($embeddings)->toHaveCount(3)
        ->and(Embedding::count())->toBe(3);
});

test('model can get embeddings', function () {
    $article = new class extends Illuminate\Database\Eloquent\Model
    {
        use HasEmbeddings;

        protected $table = 'articles';

        public function getConnectionName()
        {
            return config('sqlite-vector.connection');
        }
    };

    $article->title = 'Test Article';
    $article->save();

    $vectors = [
        array_fill(0, 1536, 0.1),
        array_fill(0, 1536, 0.2),
    ];

    foreach ($vectors as $vector) {
        $article->embed($vector);
    }

    $retrieved = $article->getEmbeddings();

    expect($retrieved)->toHaveCount(2);
});
