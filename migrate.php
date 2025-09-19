<?php
require __DIR__ . '/vendor/autoload.php';

use PanduputragmailCom\PhpNative\lib\Migration;

$command = $argv[1] ?? null;

if (!$command) {
    die("Usage: php migrate.php [up|down]\n");
}

$migrationPath = __DIR__ . '/src/database/migration';
$files = glob($migrationPath . '/*.php');

foreach ($files as $file) {
    require_once $file;

    $className = basename($file, '.php');

    $className = preg_replace('/^\d+(_\d+)*_/', '', $className);

    $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $className)));
    echo $className . "\n";


    if (!class_exists($className)) {
        echo "⚠️  Class $className not found in $file\n";
        continue;
    }

    $migration = new $className();

    if ($command === 'up') {
        echo "🔼 Running $className::up()\n";
        $migration->up();
    } elseif ($command === 'down') {
        echo "🔽 Running $className::down()\n";
        $migration->down();
    } else {
        echo "Unknown command: $command\n";
    }
}
