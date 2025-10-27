<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Rules;

use ArtisanBuild\EmbeddableLinks\Services\UrlParser;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmbeddableUrl implements ValidationRule
{
    protected array $allowedServices = [];

    public function __construct(array $allowedServices = [])
    {
        $this->allowedServices = $allowedServices;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parser = new UrlParser;

        if (! $parser->isValid($value)) {
            $fail("The {$attribute} must be a valid URL.");

            return;
        }

        if (empty($this->allowedServices)) {
            return;
        }

        try {
            $parsed = $parser->parse($value);

            if (! in_array($parsed->service, $this->allowedServices, true)) {
                $services = implode(', ', $this->allowedServices);
                $fail("The {$attribute} must be a URL from one of these services: {$services}.");
            }
        } catch (\Exception) {
            $fail("The {$attribute} could not be parsed.");
        }
    }
}
