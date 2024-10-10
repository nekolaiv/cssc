<?php
class Database{
    public $host = 'localhost';
    public $username = 'root';
    public $password = '';
    public $database = 'cssc';
    protected $connection;

    function connect(){
        try{
            $this->connection = new PDO(
                "mysql:$this->host;dbname=$this->database",
                $this->username,
                $this->password
            );
            echo "connected";
        } catch (PDOEXCEPTION $e){
            echo("Connection error: " . $e);
        }
        return $this->connection;
    }
}

?>