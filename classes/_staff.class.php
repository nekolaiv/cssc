<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once("database.class.php");

class Staff {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    // Create a new staff account
    public function createStaff($email, $password) {
        $query = "INSERT INTO Staff_Accounts (email, password) VALUES (:email, :password)";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        return $stmt->execute();
    }

    // Read all staff accounts
    public function getAllStaff() {
        $query = "SELECT * FROM Staff_Accounts";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get staff details by ID
    public function getStaffById($id) {
        $query = "SELECT * FROM Staff_Accounts WHERE staff_id = :id";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a staff profile
    public function updateStaff($id, $data) {
        $query = "UPDATE Staff_Accounts SET email = :email, role = :role WHERE staff_id = :id";
        $stmt = $this->database->connect()->prepare($query);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    // Delete a staff account
    public function deleteStaff($id) {
        $query = "DELETE FROM Staff_Accounts WHERE staff_id = :id";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>