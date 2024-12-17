<?php
class Database{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'ccs_system';
    protected $connection;

    function connect(){
        try{
            $this->connection = new PDO(
                "mysql:host=$this->host;dbname=$this->database",
                $this->username,
                $this->password
            );
        } catch (PDOException $e){
            echo "Connection error: " . $e->getMessage();
        }
        return $this->connection;
    }

     // Execute query (for INSERT, UPDATE, DELETE)
     public function execute($query, $params = []) {
        try {
            $stmt = $this->connect()->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("Query execution failed: " . $e->getMessage());
        }
    }

    // Fetch multiple rows (for SELECT queries)
    public function fetchAll($query, $params = []) {
        try {
            $stmt = $this->connect()->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Fetch failed: " . $e->getMessage());
        }
    }

    // Fetch a single row (optional helper method)
    public function fetch($query, $params = []) {
        try {
            $stmt = $this->connect()->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            die("Fetch failed: " . $e->getMessage());
        }
    }

    public function fetchOne($query, $params = []) {
        try {
            $stmt = $this->connect()->prepare($query); // Prepare the query
            $stmt->execute($params);            // Execute with parameters
            return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row as an associative array
        } catch (PDOException $e) {
            // Log the error for debugging
            return false; // Return false on failure
        }
    }

    public function fetchColumn($query, $params = [])
{
    try {
        $stmt = $this->connect()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        // Log the error and handle it appropriately
        throw new Exception("Database query failed.");
    }
}

}

?>