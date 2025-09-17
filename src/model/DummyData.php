<?php

namespace PanduputragmailCom\PhpNative\Model;

use PanduputragmailCom\PhpNative\Database\Database;

class DummyData extends Database {
    protected $table = 'dummy_data';

    public function __construct()
    {
        parent::__construct(true);
    }

    public function findAll() {
        $sql = "SELECT * FROM {$this->table}";
        $result = $this->connection()->query($sql);

        if ($result->num_rows > 0) {
            $data = [];

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return $data;
        }

        return [];
    }
    public function findOne($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt =  $this->connection()->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            return $result->fetch_assoc();
        }

        return [];
    }
    public function storeData($data){
        $sql = "INSERT INTO {$this->table} (name) VALUES (?)";
        $stmt =  $this->connection()->prepare($sql);
        $stmt->bind_param("s", $data['name']);
        // var_dump($stmt);

        if ($stmt->execute()) {
            return $this->findOne($this->connection()->insert_id);
        }

        return [];
    }
}