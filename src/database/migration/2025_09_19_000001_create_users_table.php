<?php

use PanduputragmailCom\PhpNative\lib\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->executeQuery("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
    }

    public function down()
    {
        $this->executeQuery("DROP TABLE IF EXISTS users");
    }
}
