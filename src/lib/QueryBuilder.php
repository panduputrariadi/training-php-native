<?php
namespace PanduputragmailCom\PhpNative\lib;

use PanduputragmailCom\PhpNative\Database\Database;

class QueryBuilder {
    private string $table;
    private string $query = ''; 
    private array $bindings = [];
    private array $joins = [];
    private ?string $primaryKey = 'id';
    

    public function table(string $table): self  
    {  
        $this->table = $table;  
        return $this;  
    }

    public function select(array $columns = ['*']): self  
    {  
        $columnsList = implode(', ', $columns);  
        $this->query = "SELECT $columnsList FROM {$this->table}";  
        return $this;  
    }

    public function where(string $column, string $operator, mixed $value): self  
    {  
        $placeholder = ':' . $column;  
        $this->query .= (str_contains($this->query, 'WHERE') ? " AND" : " WHERE") . " $column $operator $placeholder";  
        $this->bindings[$placeholder] = $value;  
        return $this;  
    }
    public function whereIn(string $column, array $values): self
    {
        if (empty($values)) {
            $this->query .= " AND 1=0";
            return $this;
        }

        $placeholders = [];
        foreach ($values as $value) {
            $placeholder = ':' . $column . '_' . uniqid();
            $placeholders[] = $placeholder;
            $this->bindings[$placeholder] = $value;
        }
        $inClause = implode(', ', $placeholders);
        $connector = str_contains($this->query, 'WHERE') ? ' AND' : ' WHERE';
        $this->query .= "$connector $column IN ($inClause)";
        return $this;
    }

    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
    {
        $this->joins[] = compact('type', 'table', 'first', 'operator', 'second');
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }
    public function limit(int $limit): self
    {
        $this->query .= " LIMIT $limit";
        return $this;
    }
    public function first(): ?array
    {
        $result = $this->limit(1)->get();
        return $result[0] ?? null;
    }

    public function get(): array  
    {
        $database = new Database(silent: true);
        $connection = $database->connection(); // mysqli instance
        
        $sql = $this->query;
        $values = [];

        foreach ($this->bindings as $placeholder => $value) {
            $sql = str_replace($placeholder, '?', $sql);
            $values[] = $value;
        }

        $stmt = $connection->prepare($sql);
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $connection->error);
        }

        if (!empty($values)) {            
            $types = str_repeat("s", count($values));
            $stmt->bind_param($types, ...$values);
        }

        if (!$stmt->execute()) {
            throw new \Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if (!$result) {
            return [];
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
}