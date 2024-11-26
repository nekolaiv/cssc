<?php
class Database{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'cssc';
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
}

?>