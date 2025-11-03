<?php

declare(strict_types=1);

return [
    // Whether to pretty-print JSON output
    'pretty_print' => env('JSON_MARKDOWN_PRETTY_PRINT', true),

    // File extensions to process in directory operations
    'extensions' => ['.md', '.markdown'],

    // Whether to overwrite existing files in fromJson operations
    'overwrite' => true,
];
