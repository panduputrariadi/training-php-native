<?php
namespace PanduputragmailCom\PhpNative\Database;

use mysqli;
use PanduputragmailCom\PhpNative\lib\LoadEnv;

class Database
{
    private $connection;

    public function __construct($silent = false){
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

        // echo "Berhasil terkoneksi";
        if(!$silent){
            echo "berhasil terkoneksi";
        }
    }

    public function connection()
    {
        return $this->connection;
    }
}
