<?php
namespace PanduputragmailCom\PhpNative\lib;

use mysqli;
use PanduputragmailCom\PhpNative\Database\Database;
use Exception;
use InvalidArgumentException;

class QueryBuilder
{
    private string $table;
    private string $query = '';
    private array $bindings = [];
    private array $joins = [];
    private mysqli $connection;

    public function __construct(?mysqli $connection = null)
    {
        $this->connection = $connection ?? Database::getInstance(true)->connection();
    }

    public function table(string $table): self
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            throw new InvalidArgumentException("Nama tabel tidak valid: $table");
        }
        $this->table = $table;
        return $this;
    }

    public function select(array $columns = ['*']): self
    {
        foreach ($columns as $col) {
            if (!preg_match('/^[a-zA-Z0-9_*.`]+$/', $col)) {
                throw new InvalidArgumentException("Kolom tidak valid: $col");
            }
        }
        $columnsList = implode(', ', $columns);
        $this->query = "SELECT $columnsList FROM `{$this->table}`";
        return $this;
    }

    public function where(string $column, string $operator, mixed $value): self
    {
        if (!in_array($operator, ['=', '!=', '<', '>', '<=', '>=', 'LIKE'])) {
            throw new InvalidArgumentException("Operator tidak didukung: $operator");
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
            throw new InvalidArgumentException("Nama kolom tidak valid: $column");
        }

        $this->query .= (str_contains($this->query, 'WHERE') ? ' AND' : ' WHERE') . " `$column` $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        if (empty($values)) {
            $this->query .= " AND 1=0";
            return $this;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
            throw new InvalidArgumentException("Nama kolom tidak valid: $column");
        }

        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        $connector = str_contains($this->query, 'WHERE') ? ' AND' : ' WHERE';
        $this->query .= "$connector `$column` IN ($placeholders)";
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }

    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            throw new InvalidArgumentException("Nama tabel join tidak valid");
        }
        if (!preg_match('/^[a-zA-Z0-9_.]+$/', $first) || !preg_match('/^[a-zA-Z0-9_.]+$/', $second)) {
            throw new InvalidArgumentException("Kolom join tidak valid");
        }
        if (!in_array(strtoupper($type), ['INNER', 'LEFT', 'RIGHT', 'FULL'])) {
            throw new InvalidArgumentException("Tipe join tidak didukung");
        }

        $this->joins[] = compact('type', 'table', 'first', 'operator', 'second');
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function limit(int $limit): self
    {
        $this->query .= " LIMIT " . (int)$limit;
        return $this;
    }

    public function first(): ?array
    {
        $result = $this->limit(1)->get();
        return $result[0] ?? null;
    }

    public function get(): array
    {
        $sql = $this->buildQuery();

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare gagal: " . $this->connection->error);
        }

        if (!empty($this->bindings)) {
            $types = $this->determineBindingTypes($this->bindings);
            $stmt->bind_param($types, ...$this->bindings);
        }

        if (!$stmt->execute()) {
            throw new Exception("Eksekusi gagal: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $this->resetQuery();
        return $data;
    }

    public function insert(array $data): bool
    {
        $keys = array_keys($data);
        foreach ($keys as $key) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
                throw new InvalidArgumentException("Nama kolom tidak valid untuk insert: $key");
            }
        }

        $placeholders = str_repeat('?,', count($data) - 1) . '?';
        $fieldList = '`' . implode('`, `', $keys) . '`';

        $this->query = "INSERT INTO `{$this->table}` ($fieldList) VALUES ($placeholders)";
        $this->bindings = array_values($data);

        return $this->execute();
    }

    public function insertGetId(array $data): int
    {
        $this->insert($data);
        $id = $this->connection->insert_id;
        $this->resetQuery();
        return $id;
    }

    private function buildQuery(): string
    {
        $sql = $this->query;
        foreach ($this->joins as $join) {
            $sql .= " {$join['type']} JOIN `{$join['table']}` ON {$join['first']} {$join['operator']} {$join['second']}";
        }

        return $sql;
    }

    private function execute(): bool
    {
        $sql = $this->buildQuery();
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare gagal: " . $this->connection->error);
        }

        if (!empty($this->bindings)) {
            $types = $this->determineBindingTypes($this->bindings);
            $stmt->bind_param($types, ...$this->bindings);
        }

        $success = $stmt->execute();
        $this->resetQuery();
        return $success;
    }

    private function determineBindingTypes(array $values): string
    {
        $types = '';
        foreach ($values as $value) {
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_float($value)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        return $types;
    }

    private function resetQuery(): void
    {
        $this->query = '';
        $this->bindings = [];
        $this->joins = [];
    }

    public function getConnectionForDebug(): mysqli
    {
        return $this->connection;
    }
    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}