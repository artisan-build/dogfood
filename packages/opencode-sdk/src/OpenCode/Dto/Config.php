<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Config extends SpatieData
{
    public function __construct(
        #[MapName('$schema')]
        public ?string $schema = null,
        public ?string $theme = null,
        public ?KeybindsConfig $keybinds = null,
        public ?object $tui = null,
        public ?object $command = null,
        public ?object $watcher = null,
        public ?array $plugin = null,
        public ?bool $snapshot = null,
        public ?string $share = null,
        public ?bool $autoshare = null,
        public ?bool $autoupdate = null,
        #[MapName('disabled_providers')]
        public ?array $disabledProviders = null,
        public ?string $model = null,
        #[MapName('small_model')]
        public ?string $smallModel = null,
        public ?string $username = null,
        public ?object $mode = null,
        public ?object $agent = null,
        public ?object $provider = null,
        public ?object $mcp = null,
        public ?object $formatter = null,
        public ?object $lsp = null,
        public ?array $instructions = null,
        public ?LayoutConfig $layout = null,
        public ?object $permission = null,
        public ?object $tools = null,
        public ?object $experimental = null,
    ) {}
}
