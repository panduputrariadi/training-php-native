<?php
namespace PanduputragmailCom\PhpNative\Database;

use mysqli;
use PanduputragmailCom\PhpNative\lib\LoadEnv;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct($silent = false){
        LoadEnv::loadEnv(__DIR__ . '/../../.env');  
        $host     = getenv('DB_HOST');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');
        $database = getenv('DB_NAME');
        $port     = getenv('DB_PORT');      

        $connect = new mysqli(
            $host,
            $username,
            $password,
            $database,
            $port
        );

        if ($connect->connect_error) {
            die("Koneksi gagal:  {$connect->connect_error}");
        }

        $this->connection = $connect;

        if(!$silent){
            echo "[KONEKSI BARU DIBUAT] - " . date('H:i:s') . " - PID: " . getmypid() . "\n";
        }
    }

    public static function getInstance($silent = false): self
    {
        if (self::$instance === null) {
            self::$instance = new self($silent);
        }
        return self::$instance;
    }

    public function connection()
    {
        return $this->connection;
    }

    public function close()
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }
}