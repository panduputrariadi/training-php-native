<?php

namespace PanduputragmailCom\PhpNative\lib;

use PanduputragmailCom\PhpNative\Database\Database;

abstract class Migration {
    abstract public function up();
    abstract public function down();

    protected $db;

    public function __construct() {
        $database = new Database(silent: true);
        $this->db = $database->connection();
    }

    protected function executeQuery(string $query) {
        if ($this->db->query($query) === true) {
            echo "Migration success\n";
        } else {
            echo "Migrate Error: " . $this->db->error . "\n";
        }
    }
}