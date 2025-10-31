<?php
// /app/models/BaseModel.php

class BaseModel {
    protected $db;
    protected $table;
    protected $primaryKey;

    public function __construct(string $table, string $primaryKey = null) {
        $this->db = Database::getInstance()->getConnection();
        $this->table = $table;
        $this->primaryKey = $primaryKey ?: 'id_' . $table;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Añadir alias 'id' para compatibilidad con vistas que usan $row['id']
        foreach ($rows as &$row) {
            if (isset($row[$this->primaryKey])) {
                $row['id'] = $row[$this->primaryKey];
            }
        }
        return $rows;
    }

    public function all() {
        return $this->getAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && isset($row[$this->primaryKey])) {
            $row['id'] = $row[$this->primaryKey];
        }
        return $row;
    }

    // Método genérico de inserción (Necesario para Moto y Cliente)
    public function create(array $data) {
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Limpia los datos: quita solo campos null, permite strings vacías
    $data = array_filter($data, function ($v) {
        return $v !== null;
    });


    $fields = array_keys($data);
    $columns = implode(', ', $fields);
    $placeholders = implode(', ', array_map(fn($f) => ":$f", $fields));

    $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

    $stmt = $this->db->prepare($sql);

    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();
    return $this->db->lastInsertId();
}


    // Método para contar registros con condiciones
    public function count(array $conditions = []) {
        $whereClause = '';
        $params = [];

        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $field => $value) {
                $whereParts[] = "{$field} = :{$field}";
                $params[":{$field}"] = $value;
            }
            $whereClause = ' WHERE ' . implode(' AND ', $whereParts);
        }

        $sql = "SELECT COUNT(*) as total FROM {$this->table}{$whereClause}";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && isset($row[$this->primaryKey])) {
            $row['id'] = $row[$this->primaryKey];
        }
        return $row;
    }

    public function update($id, array $data) {
        $fields = array_keys($data);
        $setClause = implode(', ', array_map(fn($field) => "{$field} = :{$field}", $fields));

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        foreach ($data as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }

        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Método para consultas con WHERE
    public function where(array $conditions) {
        $this->whereConditions = $conditions;
        return $this;
    }

    // Ejecutar consulta con WHERE y ORDER BY
    public function get() {
        $whereClause = '';
        $params = [];

        if (!empty($this->whereConditions)) {
            $whereParts = [];
            foreach ($this->whereConditions as $field => $value) {
                $whereParts[] = "{$field} = :{$field}";
                $params[":{$field}"] = $value;
            }
            $whereClause = ' WHERE ' . implode(' AND ', $whereParts);
        }

        $sql = "SELECT * FROM {$this->table}{$whereClause}{$this->orderByClause}";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->whereConditions = []; // Reset for next query
        $this->orderByClause = ''; // Reset for next query
        foreach ($rows as &$row) {
            if (isset($row[$this->primaryKey])) {
                $row['id'] = $row[$this->primaryKey];
            }
        }
        return $rows;
    }

    // Obtener el primer resultado de la consulta
    public function first() {
        $results = $this->get();
        return !empty($results) ? $results[0] : null;
    }

    // Método para consultas con WHERE raw
    public function whereRaw($whereClause, array $params = []) {
        $this->whereRawClause = $whereClause;
        $this->whereRawParams = $params;
        return $this;
    }

    // Ejecutar consulta con WHERE raw
    public function getRaw() {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->whereRawClause}{$this->orderByClause}";
        $stmt = $this->db->prepare($sql);

        foreach ($this->whereRawParams as $param => $value) {
            $stmt->bindValue($param, $value);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->whereRawClause = ''; // Reset
        $this->whereRawParams = []; // Reset
        $this->orderByClause = ''; // Reset
        foreach ($rows as &$row) {
            if (isset($row[$this->primaryKey])) {
                $row['id'] = $row[$this->primaryKey];
            }
        }
        return $rows;
    }

    protected $whereConditions = [];
    protected $orderByClause = '';

    // Método para ORDER BY
    public function orderBy($field, $direction = 'ASC') {
        $this->orderByClause = " ORDER BY {$field} {$direction}";
        return $this;
    }

    protected $whereRawClause = '';
    protected $whereRawParams = [];

    // Método para acceder a la conexión de base de datos
    public function getDb() {
        return $this->db;
    }
}
