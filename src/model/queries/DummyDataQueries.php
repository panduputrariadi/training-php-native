<?php

namespace PanduputragmailCom\PhpNative\model\queries;

use mysqli;
use PanduputragmailCom\PhpNative\Database\Database;
use PanduputragmailCom\PhpNative\lib\QueryBuilder;
use PanduputragmailCom\PhpNative\Model\DummyData;

class DummyDataQueries
{
    protected $model;
    protected mysqli $connection;

    public function __construct(DummyData $model)
    {
        $this->model = $model;
        $this->connection = Database::getInstance(true)->connection();
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
        $stmt = $this->connection->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->connection->error);
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
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder->table($this->model->getTable());
        
        $insertId = $queryBuilder->insertGetId($data);
        return $this->getOneData($insertId);
    }

    public function connection(): mysqli
    {
        return $this->connection;
    }
}