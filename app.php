<?php

declare(strict_types=1);

namespace App;

use App\Handler\CliHandler;

require __DIR__ . '/vendor/autoload.php';

$handler = new CliHandler();
$handler->setInput($argv, $argc);
$command = $handler->getCommand();

try {
    $commissions = $command->run($handler->argument);
    foreach ($commissions as $commission) {
        if ($commission != '') {
            echo $commission . \PHP_EOL;
        }
    }
} catch (\Exception $e) {
    echo "ERROR: {$e->getMessage()}" . \PHP_EOL;
}
