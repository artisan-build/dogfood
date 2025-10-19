<?php

declare(strict_types=1);

require __DIR__.'/../../vendor/autoload.php';

$config = require __DIR__.'/saloon-sdk-generator.php';

foreach ($config as $generator) {
    echo "Running generator: {$generator->name}\n";
    $generator->run();
    echo "✓ SDK generated successfully!\n";
}
