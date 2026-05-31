<?php
/**
 * Database Class
 * Handles all database operations
 */

class Database {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    /**
     * Execute Query
     */
    public function query($sql) {
        return $this->conn->query($sql);
    }
    
    /**
     * Prepare Statement
     */
    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }
    
    /**
     * Get Single Row
     */
    public function getRow($sql) {
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Get All Rows
     */
    public function getAll($sql) {
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Insert Data
     */
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $values = implode("', '", array_values($data));
        $sql = "INSERT INTO $table ($columns) VALUES ('$values')";
        
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    /**
     * Update Data
     */
    public function update($table, $data, $condition) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = '$value'";
        }
        $set = implode(", ", $set);
        $sql = "UPDATE $table SET $set WHERE $condition";
        
        return $this->conn->query($sql);
    }
    
    /**
     * Delete Data
     */
    public function delete($table, $condition) {
        $sql = "DELETE FROM $table WHERE $condition";
        return $this->conn->query($sql);
    }
    
    /**
     * Count Records
     */
    public function count($table, $condition = "") {
        $where = $condition ? "WHERE $condition" : "";
        $sql = "SELECT COUNT(*) as count FROM $table $where";
        $result = $this->getRow($sql);
        return $result ? $result['count'] : 0;
    }
    
    /**
     * Escape String
     */
    public function escape($string) {
        return $this->conn->real_escape_string($string);
    }
    
    /**
     * Close Connection
     */
    public function close() {
        $this->conn->close();
    }
    
    /**
     * Get Last Error
     */
    public function getLastError() {
        return $this->conn->error;
    }
}

?>