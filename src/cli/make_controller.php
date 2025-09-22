<?php

require __DIR__ . '/../../booststrap/app.php';

function studly_case(string $value): string {
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value)));
}

if ($argc < 3) {
    echo "Usage: php make_controller.php <ModelName>\n";
    echo "Example: php make_controller.php User\n";
    exit(1);
}

$controller = studly_case($argv[2]);

$filePath = __DIR__ . '/../controller/' . $controller . '.php';

$template = <<<PHP
<?php

namespace PanduputragmailCom\\PhpNative\\Controller;

class {$controller}
{    

    // TODO: Define methods here
}

PHP;

$dir = dirname($filePath);
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

if (file_put_contents($filePath, $template)) {
    echo "✅ Controller created successfully: {$filePath}\n";
} else {
    echo "❌ Failed to create Controller: {$modelName}\n";
    exit(1);
}