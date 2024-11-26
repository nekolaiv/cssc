<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once("database.class.php");

class Admin {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    // Create a new admin account
    public function createAdmin($email, $password) {
        $query = "INSERT INTO Admin_Accounts (email, password) VALUES (:email, :password)";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        return $stmt->execute();
    }

    // Read all admins
    public function getAllAdmins() {
        $query = "SELECT * FROM Admin_Accounts";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get admin by ID
    public function getAdminById($id) {
        $query = "SELECT * FROM Admin_Accounts WHERE admin_id = :id";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update admin profile
    public function updateAdmin($id, $email) {
        $query = "UPDATE Admin_Accounts SET email = :email WHERE admin_id = :id";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    // Delete an admin account
    public function deleteAdmin($id) {
        $query = "DELETE FROM Admin_Accounts WHERE admin_id = :id";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>