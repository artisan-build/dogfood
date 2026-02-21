<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | The database connection to use for storing embeddings. This allows you
    | to use a separate SQLite database for embeddings even if your primary
    | database is MySQL, PostgreSQL, or another SQLite database.
    |
    */
    'connection' => env('SQLITE_VEC_CONNECTION', 'sqlite'),

    /*
    |--------------------------------------------------------------------------
    | Extension Path
    |--------------------------------------------------------------------------
    |
    | The path to the sqlite-vec extension file. This is automatically
    | determined based on your operating system when you run the
    | sqlite-vec:install command.
    |
    */
    'extension_path' => storage_path('sqlite-vec/vec0'.PHP_SHLIB_SUFFIX),

    /*
    |--------------------------------------------------------------------------
    | Default Vector Dimensions
    |--------------------------------------------------------------------------
    |
    | The default number of dimensions for embeddings. This corresponds to
    | the embedding model you're using. For example, OpenAI's
    | text-embedding-ada-002 produces 1536-dimensional vectors.
    |
    */
    'default_dimensions' => 1536,

    /*
    |--------------------------------------------------------------------------
    | Virtual Table Name
    |--------------------------------------------------------------------------
    |
    | The name of the virtual table that stores the embedding vectors.
    |
    */
    'table_name' => 'embeddings',

    /*
    |--------------------------------------------------------------------------
    | Metadata Table Name
    |--------------------------------------------------------------------------
    |
    | The name of the metadata table that stores polymorphic relationships
    | and additional information about each embedding.
    |
    */
    'metadata_table_name' => 'embedding_metadata',

    /*
    |--------------------------------------------------------------------------
    | Distance Metric
    |--------------------------------------------------------------------------
    |
    | The default distance metric to use for similarity searches.
    | Supported values: 'cosine', 'l2', 'l1'
    |
    | - cosine: Best for normalized vectors (most common)
    | - l2: Euclidean distance
    | - l1: Manhattan distance
    |
    */
    'distance_metric' => 'cosine',

    /*
    |--------------------------------------------------------------------------
    | Auto Load Extension
    |--------------------------------------------------------------------------
    |
    | Whether to automatically load the sqlite-vec extension when the
    | configured database connection is first established.
    |
    */
    'auto_load_extension' => true,
];
