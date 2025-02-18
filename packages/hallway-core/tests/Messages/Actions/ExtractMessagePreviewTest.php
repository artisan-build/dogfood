<?php

declare(strict_types=1);

use ArtisanBuild\Hallway\Messages\Actions\ExtractMessagePreview;

describe('extract message preview from full message body', function (): void {
    it('simply returns the full body if under the limit', function (): void {
        $short = file_get_contents(__DIR__ . '/__data/short.html');

        expect(trim(app(ExtractMessagePreview::class)($short, 500)))->toBe(trim($short));
    });

    it('truncates a long string and then closes all the open tags', function (): void {
        $long = file_get_contents(__DIR__ . '/__data/long.hmtl');

        expect(trim(app(ExtractMessagePreview::class)($long, 500)))->toBe('<p><strong>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sed ultricies libero. Cras aliquam vel mi nec mattis. Quisque sed dui neque. Aenean rhoncus massa id nisi consectetur bibendum. Ut ac odio vel orci condimentum dignissim eget vitae ligula. Quisque sed lobortis nunc. Suspendisse mollis tincidunt egestas. Sed scelerisque semper nulla quis molestie. Quisque malesuada non risus in feugiat. Vivamus fermentum magna ut nulla sagittis dictum. Ut orci erat, scelerisque id dolor eget, lacinia m</strong></p>');
    });
});
