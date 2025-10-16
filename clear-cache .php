<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$commands = [
    'config:clear',
    'cache:clear',
    'view:clear',
    'route:clear',
    'optimize:clear',
];

foreach ($commands as $command) {
    echo "Running $command...<br>";
    $kernel->call($command);
    echo nl2br($kernel->output()) . "<br><br>";
}

echo "<strong>✅ Cache cleared successfully!</strong>";
