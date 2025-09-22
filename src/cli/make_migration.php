<?php

// Autoload composer
require __DIR__ . '/../../booststrap/app.php';

function studly_case(string $value): string {
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value)));
}

function snake_case(string $value): string {
    $value = preg_replace('/([a-z])([A-Z])/', '$1_$2', $value);
    return strtolower($value);
}

if ($argc < 2) {
    echo "Usage: php make_migration.php <name_of_migration>\n";
    echo "Example: php make_migration.php create_users_table\n";
    exit(1);
}

$name = $argv[1];

$timestamp = date('Y_m_d_His');

// Nama file
$fileName = $timestamp . '_' . snake_case($name) . '.php';

// ganti kalo perlu
$className = studly_case($name);

// Path file
$path = __DIR__ . '/../database/migration/' . $fileName;

// Template isi migration
$template = <<<PHP
<?php

use PanduputragmailCom\PhpNative\lib\Migration;

class {$className} extends Migration
{
    public function up()
    {
        // TODO: isi migration, contoh:
        // \$this->executeQuery("CREATE TABLE example (id INT AUTO_INCREMENT PRIMARY KEY)");
    }

    public function down()
    {
        // TODO: rollback migration, contoh:
        // \$this->executeQuery("DROP TABLE example");
    }
}
PHP;

// Buat file, 0777 artinya bisa akses read dan write
if (!is_dir(dirname($path))) {
    mkdir(dirname($path), 0777, true);
}

file_put_contents($path, $template);

echo "Migration created: {$path}\n";
