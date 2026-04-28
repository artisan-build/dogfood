<?php

declare(strict_types=1);

use ArtisanBuild\SqliteVector\Models\Embedding;
use Illuminate\Database\Eloquent\Relations\MorphTo;

test('embedding model uses configured connection', function (): void {
    $embedding = new Embedding;
    expect($embedding->getConnectionName())->toBe('sqlite');
});

test('embedding model uses configured table name', function (): void {
    $embedding = new Embedding;
    expect($embedding->getTable())->toBe('embedding_metadata');
});

test('embedding model casts metadata as array', function (): void {
    $embedding = new Embedding;
    expect($embedding->getCasts())->toHaveKey('metadata', 'array');
});

test('embedding model casts embedded_at as datetime', function (): void {
    $embedding = new Embedding;
    expect($embedding->getCasts())->toHaveKey('embedded_at', 'datetime');
});

test('embedding model has embeddable morphTo relationship', function (): void {
    $embedding = new Embedding;
    expect($embedding->embeddable())->toBeInstanceOf(MorphTo::class);
});
