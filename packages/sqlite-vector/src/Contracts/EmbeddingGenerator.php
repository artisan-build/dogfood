<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector\Contracts;

interface EmbeddingGenerator
{
    /**
     * Generate an embedding vector for the given text.
     *
     * @param  string  $text  The text to generate an embedding for
     * @return array The embedding vector
     */
    public function generate(string $text): array;

    /**
     * Generate embedding vectors for multiple texts.
     *
     * @param  array  $texts  The texts to generate embeddings for
     * @return array Array of embedding vectors
     */
    public function generateBatch(array $texts): array;

    /**
     * Get the dimensions of the embeddings this generator produces.
     */
    public function dimensions(): int;

    /**
     * Get the model name/identifier for this generator.
     */
    public function model(): string;
}
