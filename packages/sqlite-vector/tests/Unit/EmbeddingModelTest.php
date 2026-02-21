<?php

declare(strict_types=1);

use ArtisanBuild\SqliteVector\Models\Embedding;

test('embedding model uses configured connection', function () {
    $embedding = new Embedding;
    expect($embedding->getConnectionName())->toBe('sqlite');
});

test('embedding model uses configured table name', function () {
    $embedding = new Embedding;
    expect($embedding->getTable())->toBe('embedding_metadata');
});

test('embedding model casts metadata as array', function () {
    $embedding = new Embedding;
    expect($embedding->getCasts())->toHaveKey('metadata', 'array');
});

test('embedding model casts embedded_at as datetime', function () {
    $embedding = new Embedding;
    expect($embedding->getCasts())->toHaveKey('embedded_at', 'datetime');
});

test('embedding model has embeddable morphTo relationship', function () {
    $embedding = new Embedding;
    expect($embedding->embeddable())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphTo::class);
});
