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
        $queryBuilder = new QueryBuilder($this->connection);

        $data = $queryBuilder
            ->table($this->model->getTable())
            ->select(['*'])
            ->get();
        
        return $data;
    }

    public function getOneData($id): array
    {
        $result = (new QueryBuilder($this->connection))
            ->table($this->model->getTable())
            ->select()
            ->where('id', '=', $id)
            ->first();

        return $result ?: [];
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

    public function debugConnectionInfo(): void
    {
        echo "Connection in DummyDataQueries: " . spl_object_hash($this->connection) . "\n";
        echo "Thread ID: " . $this->connection->thread_id . "\n";
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}