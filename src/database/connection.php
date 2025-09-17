<?php
class Database
{
    private $host     = 'localhost';
    private $username = 'root';
    private $password = 'passwordbaru';
    // private $password = 'yourpassword';
    private $database = 'php_native';
    private $port     = 3306;
    private $connection;

    public function __construct()
    {
        $connect = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database,
            $this->port           
        );

        if ($connect->connect_error) {
            die("Koneksi gagal:  {$connect->connect_error}");
        }

        $this->connection = $connect;

        echo "Berhasil terkoneksi";
    }

    public function connection()
    {
        return $this->connection;
    }
}
