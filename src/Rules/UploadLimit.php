<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UploadLimit implements ValidationRule
{
    private static array $allowedMimetypes = [
        'images' => [
            'mimetypes' => [
                'image/jpg',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/svg',
                'image/svg+xml',
            ],
            'max' => 5 * 1024,
        ],
        'documents' => [
            'mimetypes' => [
                'application/pdf',
                'text/csv',
                'text/plain',
            ],
            'max' => 20 * 1024,
        ],
    ];

    public static function allowedMimeTypes(): array
    {
        return array_merge(...array_column(self::$allowedMimetypes, 'mimetypes'));
    }

    public static function getMimeTypeMax(string $mimeType): int
    {

        foreach (self::$allowedMimetypes as $mimeTypeGroup) {
            if (in_array($mimeType, $mimeTypeGroup['mimetypes'])) {
                return $mimeTypeGroup['max'];
            }
        }

        // if mimetype not found, set max to 0
        return 0;
    }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $filesize = filesize($value->path()) / 1024; // get filesize and convert to kb
        $mimetype = mime_content_type($value->path());

        if ($filesize > self::getMimeTypeMax($mimetype)) {
            $fail('The :attribute exceeds the allowed file size.');
        }
    }

}
