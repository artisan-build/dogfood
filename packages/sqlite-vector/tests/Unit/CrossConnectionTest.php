<?php

declare(strict_types=1);

use ArtisanBuild\SqliteVector\EmbeddingManager;
use ArtisanBuild\SqliteVector\Models\Embedding;
use ArtisanBuild\SqliteVector\SearchQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

beforeEach(function (): void {
    // Set up TWO different database connections to test cross-connection support
    // Connection 1: 'primary' - simulates app's main MySQL/PostgreSQL database
    config()->set('database.connections.primary', [
        'driver' => 'sqlite',
        'database' => ':memory:',
    ]);

    // Connection 2: 'vector_db' - dedicated SQLite connection for embeddings
    config()->set('database.connections.vector_db', [
        'driver' => 'sqlite',
        'database' => ':memory:',
    ]);

    // Configure package to use the vector_db connection
    config()->set('sqlite-vector.connection', 'vector_db');

    // Create articles table on PRIMARY connection
    DB::connection('primary')->statement('
        CREATE TABLE IF NOT EXISTS articles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT,
            content TEXT,
            created_at TEXT,
            updated_at TEXT
        )
    ');

    // Create embedding tables on VECTOR_DB connection
    $metadataTableName = config('sqlite-vector.metadata_table_name');
    DB::connection('vector_db')->statement("
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

    $tableName = config('sqlite-vector.table_name');
    DB::connection('vector_db')->statement(
        "CREATE TABLE IF NOT EXISTS {$tableName} (rowid INTEGER PRIMARY KEY, embedding TEXT)"
    );
});

test('embeddings are stored on configured connection not model connection', function (): void {
    // Create article on PRIMARY connection
    $article = new class extends Model
    {
        protected $table = 'articles';

        protected $connection = 'primary';
    };

    $article->title = 'Test Article';
    $article->save();

    expect($article->getConnectionName())->toBe('primary');

    // Embed the article - embedding should go to vector_db connection
    $manager = new EmbeddingManager;
    $vector = array_fill(0, 1536, 0.1);
    $embedding = $manager->store($article, $vector);

    // Verify embedding was created on vector_db connection
    expect($embedding->getConnectionName())->toBe('vector_db');

    // Verify article still exists on primary connection
    $articleCheck = DB::connection('primary')
        ->table('articles')
        ->where('id', $article->id)
        ->first();

    expect($articleCheck)->not->toBeNull()
        ->and($articleCheck->title)->toBe('Test Article');

    // Verify embedding exists on vector_db connection
    $embeddingCheck = DB::connection('vector_db')
        ->table(config('sqlite-vector.metadata_table_name'))
        ->where('id', $embedding->id)
        ->first();

    expect($embeddingCheck)->not->toBeNull()
        ->and($embeddingCheck->embeddable_id)->toBe($article->id);
});

test('polymorphic relationships work across connections', function (): void {
    // Create article on PRIMARY connection
    $article = new class extends Model
    {
        protected $table = 'articles';

        protected $connection = 'primary';
    };

    $article->title = 'Cross-Connection Article';
    $article->save();

    // Create embedding on vector_db connection
    $manager = new EmbeddingManager;
    $vector = array_fill(0, 1536, 0.2);
    $embedding = $manager->store($article, $vector, ['test' => 'cross-connection']);

    // Verify polymorphic relationship works
    // The embeddable should be the article from the primary connection
    $embeddable = $embedding->embeddable;

    expect($embeddable)->not->toBeNull()
        ->and($embeddable->id)->toBe($article->id)
        ->and($embeddable->title)->toBe('Cross-Connection Article')
        ->and($embeddable->getConnectionName())->toBe('primary');
});

test('search works with cross-connection setup', function (): void {
    // Create article on PRIMARY connection
    $article = new class extends Model
    {
        protected $table = 'articles';

        protected $connection = 'primary';
    };

    $article->title = 'Search Test Article';
    $article->save();

    // Create embeddings on vector_db connection
    $manager = new EmbeddingManager;
    $vectors = [
        array_fill(0, 1536, 0.1),
        array_fill(0, 1536, 0.2),
    ];

    foreach ($vectors as $index => $vector) {
        $manager->store($article, $vector, ['chunk' => $index + 1]);
    }

    // Search should work even though article is on different connection
    $queryVector = array_fill(0, 1536, 0.15);
    $builder = new SearchQueryBuilder($queryVector);

    $results = $builder->limit(2)->get();

    expect($results)->toHaveCount(2);
});

test('migrations create tables on configured connection', function (): void {
    // Verify metadata table exists on vector_db connection
    $metadataTableExists = DB::connection('vector_db')
        ->select("SELECT name FROM sqlite_master WHERE type='table' AND name=?", [
            config('sqlite-vector.metadata_table_name'),
        ]);

    expect($metadataTableExists)->not->toBeEmpty();

    // Verify metadata table does NOT exist on primary connection
    $primaryHasMetadata = DB::connection('primary')
        ->select("SELECT name FROM sqlite_master WHERE type='table' AND name=?", [
            config('sqlite-vector.metadata_table_name'),
        ]);

    expect($primaryHasMetadata)->toBeEmpty();
});

test('embedding model uses configured connection', function (): void {
    $embedding = new Embedding;

    expect($embedding->getConnectionName())->toBe('vector_db');
});
