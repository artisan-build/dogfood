<p align="center"><img src="https://github.com/artisan-build/sqlite-vector/raw/HEAD/art/sqlite-vector.png" width="75%" alt="Artisan Build Package Sqlite Vector Logo"></p>

# SQLite Vector

A Laravel package for vector similarity search using sqlite-vec, enabling semantic search and AI-powered features in your Laravel applications.

> [!WARNING]
> This package is currently under active development. We have not yet released a major version. We strongly recommend locking your application to a specific working version as we might make breaking changes even in patch releases until we've tagged 1.0.

## Features

- **Vector Similarity Search**: Store and search embedding vectors using SQLite's vec0 extension
- **Cross-Connection Support**: Store embeddings on a separate database connection from your app's primary database
- **Polymorphic Relationships**: Associate embeddings with any Eloquent model
- **Multiple Distance Metrics**: Support for L2 (Euclidean), Cosine, and L1 (Manhattan) distance calculations
- **Fluent Search API**: Chainable query builder for complex vector searches
- **Automatic Extension Installation**: Platform-specific installation command for sqlite-vec
- **BYO Embeddings**: Bring your own embedding generation (OpenAI, Prism, local models, etc.)
- **Diagnostic Tools**: Built-in command to verify extension installation and configuration

## Requirements

- PHP 8.3+
- Laravel 11.36+
- SQLite 3.x
- sqlite-vec extension (automatically installed via command)

## Installation

Install the package via Composer:

```bash
composer require artisan-build/sqlite-vector
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=sqlite-vector-config
```

Publish and run the migrations:

```bash
php artisan vendor:publish --tag=sqlite-vector-migrations
php artisan migrate
```

Install the sqlite-vec extension for your platform:

```bash
php artisan sqlite-vec:install
```

## Configuration

The package can be configured via `config/sqlite-vector.php`:

```php
return [
    // Database connection to use for embeddings (can be different from your app's primary connection)
    'connection' => env('SQLITE_VEC_CONNECTION', 'sqlite'),

    // Path to the sqlite-vec extension file
    'extension_path' => storage_path('sqlite-vec/vec0'.PHP_SHLIB_SUFFIX),

    // Default vector dimensions (adjust based on your embedding model)
    'default_dimensions' => 1536, // OpenAI text-embedding-3-small

    // Table name for the virtual vec0 table
    'table_name' => 'embeddings',

    // Table name for embedding metadata
    'metadata_table_name' => 'embedding_metadata',

    // Default distance metric: 'l2', 'cosine', or 'l1'
    'distance_metric' => 'cosine',

    // Automatically load extension when connection is established
    'auto_load_extension' => true,
];
```

### Cross-Connection Setup

To store embeddings on a separate SQLite database (recommended for apps using MySQL/PostgreSQL as primary database):

```php
// config/database.php
'connections' => [
    'mysql' => [
        // Your primary database
    ],

    'vector_db' => [
        'driver' => 'sqlite',
        'database' => database_path('vector.sqlite'),
    ],
],

// config/sqlite-vector.php
'connection' => 'vector_db',
```

## Usage

### Basic Usage

Add the `HasEmbeddings` trait to any model:

```php
use ArtisanBuild\SqliteVector\Traits\HasEmbeddings;

class Article extends Model
{
    use HasEmbeddings;
}
```

Store an embedding:

```php
// Generate your embedding vector (e.g., using OpenAI, Prism, etc.)
$vector = $embeddingService->generate($article->content);

// Store the embedding
$article->embed($vector, [
    'source' => 'content',
    'model' => 'text-embedding-3-small',
]);
```

### Search for Similar Content

```php
use ArtisanBuild\SqliteVector\SearchQueryBuilder;

$queryVector = $embeddingService->generate($searchQuery);

$results = (new SearchQueryBuilder($queryVector))
    ->usingMetric('cosine')
    ->whereMetadata('source', 'content')
    ->limit(10)
    ->get();

foreach ($results as $embedding) {
    $article = $embedding->embeddable; // Get the related article
    echo "Distance: {$embedding->distance}\n";
    echo "Title: {$article->title}\n";
}
```

### Batch Embeddings

For documents that need to be chunked:

```php
$chunks = $this->chunkDocument($article->content);
$vectors = $embeddingService->generateBatch($chunks);

$metadata = array_map(fn ($i) => ['chunk' => $i + 1], array_keys($chunks));

$article->embedBatch($vectors, $metadata);
```

### Using the Embedding Manager Directly

```php
use ArtisanBuild\SqliteVector\Facades\Embedding;

// Store
$embedding = Embedding::store($article, $vector, ['key' => 'value']);

// Update
$embedding = Embedding::update($article, $newVector, ['key' => 'new_value']);

// Delete
Embedding::deleteForModel($article);

// Get all embeddings for a model
$embeddings = Embedding::getForModel($article);
```

### Distance Metrics

Choose the appropriate distance metric for your use case:

```php
// Cosine similarity (default, best for normalized vectors)
$builder->usingMetric('cosine');

// Euclidean distance (L2 norm)
$builder->usingMetric('l2');

// Manhattan distance (L1 norm)
$builder->usingMetric('l1');
```

**When to use each metric:**
- **Cosine**: Best for semantic similarity, works well with normalized embeddings (OpenAI, most modern models)
- **L2**: Good for comparing vector magnitudes, sensitive to scale
- **L1**: Robust to outliers, faster computation for high dimensions

### Implementing an Embedding Generator

Create your own embedding generator by implementing the contract:

```php
use ArtisanBuild\SqliteVector\Contracts\EmbeddingGenerator;

class OpenAIEmbeddingGenerator implements EmbeddingGenerator
{
    public function generate(string $text): array
    {
        $response = OpenAI::embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $text,
        ]);

        return $response->embeddings[0]->embedding;
    }

    public function generateBatch(array $texts): array
    {
        $response = OpenAI::embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $texts,
        ]);

        return array_map(
            fn ($embedding) => $embedding->embedding,
            $response->embeddings
        );
    }

    public function dimensions(): int
    {
        return 1536;
    }

    public function model(): string
    {
        return 'text-embedding-3-small';
    }
}
```

## Diagnostic Command

Verify your sqlite-vec installation and configuration:

```bash
php artisan sqlite-vec:diagnose
```

This command checks:
- Configuration values
- Database connection
- Extension file existence
- Extension loading capability
- Basic vector operations
- Automatically cleans up test data

## Testing

Run the package tests:

```bash
composer test
```

## Platform Support

The installation command supports:
- **macOS**: Intel (x86_64) and Apple Silicon (ARM64)
- **Linux**: x86_64 and ARM64
- **Windows**: x86_64

## Memberware

This package is part of our internal toolkit and is optimized for our own purposes. We do not accept issues or PRs in this repository.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
