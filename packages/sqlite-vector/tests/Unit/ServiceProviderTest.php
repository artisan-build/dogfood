<?php

declare(strict_types=1);

test('service provider registers configuration', function () {
    expect(config('sqlite-vector'))->toBeArray();
});

test('configuration has connection setting', function () {
    expect(config('sqlite-vector.connection'))->toBe('sqlite');
});

test('configuration has extension_path setting', function () {
    expect(config('sqlite-vector.extension_path'))->toBeString();
});

test('configuration has default_dimensions setting', function () {
    expect(config('sqlite-vector.default_dimensions'))->toBe(1536);
});

test('configuration has table_name setting', function () {
    expect(config('sqlite-vector.table_name'))->toBe('embeddings');
});

test('configuration has metadata_table_name setting', function () {
    expect(config('sqlite-vector.metadata_table_name'))->toBe('embedding_metadata');
});

test('configuration has distance_metric setting', function () {
    expect(config('sqlite-vector.distance_metric'))->toBe('cosine');
});

test('configuration has auto_load_extension setting', function () {
    expect(config('sqlite-vector.auto_load_extension'))->toBeBool();
});
