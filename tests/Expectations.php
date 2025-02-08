<?php

declare(strict_types=1);

use ArtisanBuild\Docsidian\DocsidianPage;

// TODO: #[ForPackage('docsidian')]
expect()->extend('toProduce', function (string $expected_output, callable $using): void {
    $page = new DocsidianPage($this->value);
    $returningSelf = fn (DocsidianPage $page): DocsidianPage => $page;
    $actual_output = $using($page, $returningSelf)->markdown;

    expect($actual_output)->toBe($expected_output);
});
