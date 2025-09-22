<?php

require __DIR__ . '/../../booststrap/app.php';

function studly_case(string $value): string {
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value)));
}

if ($argc < 3) {
    echo "Usage: php make_model.php <ModelName>\n";
    echo "Example: php make_model.php User\n";
    exit(1);
}

$modelName = studly_case($argv[2]);

$filePath = __DIR__ . '/../model/' . $modelName . '.php';

$template = <<<PHP
<?php

namespace PanduputragmailCom\\PhpNative\\model;

class {$modelName}
{
    // TODO: Define properties and methods here
}

PHP;

$dir = dirname($filePath);
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

if (file_put_contents($filePath, $template)) {
    echo "✅ Model created successfully: {$filePath}\n";
} else {
    echo "❌ Failed to create model: {$modelName}\n";
    exit(1);
}