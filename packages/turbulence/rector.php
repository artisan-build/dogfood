<?php

use Rector\Config\RectorConfig;
use Spatie\StructureDiscoverer\Discover;

return RectorConfig::configure()
    ->withRules(
        (array) Discover::in(__DIR__.'/src/Rectors')->classes()->get()
    );
