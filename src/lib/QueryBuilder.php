<?php
namespace PanduputragmailCom\PhpNative\lib;

use PanduputragmailCom\PhpNative\Database\Database;

class QueryBuilder {
    private string $table;
    private string $query = ''; 
    private array $bindings = [];
    

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