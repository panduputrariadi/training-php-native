<?php

namespace PanduputragmailCom\PhpNative\model\queries;

use PanduputragmailCom\PhpNative\Database\Database;
use PanduputragmailCom\PhpNative\lib\QueryBuilder;
use PanduputragmailCom\PhpNative\Model\DummyData;

class DummyDataQueries extends Database {
    protected $model;

    public function __construct(DummyData $model) {
        // parent::__construct(true);
        $this->model = $model;
    }

    public function getAllDataUsingQueryBuilder(): array {        
        $queryBuilder = new QueryBuilder($this->connection());  

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

        $type = is_numeric($id) ? 'i' : 's';
        $stmt->bind_param($type, $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row ?: [];
        } else {
            error_log("Execute failed: " . $stmt->error);
        }      
        return [];
    }

    public function storeDataWithQueryBuilder(array $data): array
    {        
        $fillable = $this->model->getFillable();

        $invalid = array_diff(array_keys($data), $fillable);
        if (!empty($invalid)) {
            throw new \Exception("Field does not exist: " . implode(', ', $invalid));
        }

        $filteredData = array_intersect_key($data, array_flip($fillable));
        if (empty($filteredData)) {
            throw new \Exception("There are no valid fields.");
        }

        $queryBuilder = new QueryBuilder($this->connection());
        $queryBuilder->table($this->model->getTable());
        
        $insertId = $queryBuilder->insertGetId($filteredData);

        return $this->getOneData($insertId);
    }   
}
