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

    public function getOneData($id): array
    {
        $table = $this->model->getTable();
                
        $sql = "SELECT * FROM `$table` WHERE `id` = ? LIMIT 1";
        $stmt = $this->connection()->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->connection()->error);
            return [];
        }

        // i for integer, s for string
        $type = is_numeric($id) ? 'i' : 's';
        $stmt->bind_param($type, $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row ?: []; // make sure $row is array
        } else {
            error_log("Execute failed: " . $stmt->error);
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

    public function storeDataWithQueryBuilder(array $data): array
    {        
        $fillable = $this->model->getFillable();

        // check field in fillable
        $invalid = array_diff(array_keys($data), $fillable);
        if (!empty($invalid)) {
            throw new \Exception("Field does not exist: " . implode(', ', $invalid));
        }

        $filteredData = array_intersect_key($data, array_flip($fillable));
        if (empty($filteredData)) {
            throw new \Exception("There are no valid fields.");
        }

        $queryBuilder = new QueryBuilder();
        $queryBuilder->table($this->model->getTable());
        
        $insertId = $queryBuilder->insertGetId($filteredData);

        return $this->getOneData($insertId);
    }

   
}
