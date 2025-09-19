<?php

namespace PanduputragmailCom\PhpNative\model\queries;

use PanduputragmailCom\PhpNative\Database\Database;
use PanduputragmailCom\PhpNative\lib\QueryBuilder;
use PanduputragmailCom\PhpNative\Model\DummyData;

class DummyDataQueries extends Database {
    protected $model;

    public function __construct(DummyData $model) {
        parent::__construct(true);
        $this->model = $model;
    }

    public function getAllData() {
        $sql = "SELECT * FROM {$this->model->getTable()}";
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

    public function getAllDataUsingQueryBuilder(): array {
        $queryBuilder = new QueryBuilder();

        $data = $queryBuilder
            ->table($this->model->getTable())
            ->select(['*'])
            ->get();

        return $data;
    }

    public function getOneData($id) {
        $sql = "SELECT * FROM {$this->model->getTable()} WHERE id = ?";
        $stmt = $this->connection()->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        return [];
    }

    public function storeData($data) {           
        $fillable = $this->model->getFillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));

        if (empty($filteredData)) {
            throw new \Exception("No valid fields provided");
        }

        $columns = implode(", ", array_keys($filteredData));
        $placeholders = implode(", ", array_fill(0, count($filteredData), "?"));

        $sql = "INSERT INTO {$this->model->getTable()} ($columns) VALUES ($placeholders)";
        $stmt = $this->connection()->prepare($sql);

        $types = str_repeat("s", count($filteredData));
        $stmt->bind_param($types, ...array_values($filteredData));

        if ($stmt->execute()) {
            return $this->getOneData($this->connection()->insert_id);
        }

        return [];
    }
   
}
