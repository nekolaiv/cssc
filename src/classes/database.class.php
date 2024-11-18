<?php
namespace Src\Classes;
use PDO;
use PDOException;
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
}

?>