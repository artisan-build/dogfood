<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Attachments\Enums;

use Illuminate\Support\Facades\Blade;

enum AttachmentDisplayTemplates: string
{
    case Image = 'image';
    case Video = 'video';
    case Pdf = 'pdf';

    case Other = 'other';

    public function render(string $url): string
    {
        return match ($this) {
            self::Image => app()->has('message_image_template')
                ? view(app()->get('message_image_template'), ['url' => $url])->toHtml()
                : Blade::render('<img class="object-contain md:object-scale-down" src="' . $url . '" alt="">'),

            // TODO: Implement the other template options and ensure that we have them bound in the Hallway Flux provider
            default => Blade::render('<flux:link href="' . $url . '">' . $url . '</flux:link>'),
        };
    }
}
