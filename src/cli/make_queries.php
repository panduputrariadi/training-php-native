<?php

require __DIR__ . '/../../booststrap/app.php';

function studly_case(string $value): string {
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value)));
}

if ($argc < 3) {
    echo "Usage: php make_queries.php <ModelName>\n";
    echo "Example: php make_queries.php User\n";
    exit(1);
}

$queries = studly_case($argv[2]);

$filePath = __DIR__ . '/../model/queries/' . $queries . '.php';

$template = <<<PHP
<?php

namespace PanduputragmailCom\\PhpNative\\model;

use PanduputragmailCom\\PhpNative\\Database\\Database;

class {$queries} extends Database
{
    protected \$model;

    public function __construct(YourModel \$model)
    {
        parent::__construct();
        \$this->model = \$model;
    }

    // TODO: Define methods here
}

PHP;

$dir = dirname($filePath);
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

if (file_put_contents($filePath, $template)) {
    echo "✅ Queries created successfully: {$filePath}\n";
} else {
    echo "❌ Failed to create Queries: {$queries}\n";
    exit(1);
}