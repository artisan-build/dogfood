<?php

declare(strict_types=1);

if (! function_exists('bench_path')) {
    function bench_path(string $path = ''): string
    {
        return rtrim(implode(DIRECTORY_SEPARATOR, [
            config('bench.bench_directory'),
            ltrim($path, DIRECTORY_SEPARATOR)]), DIRECTORY_SEPARATOR);
    }
}
