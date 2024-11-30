<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("database.class.php");

class Entries {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    // Fetch all unverified entries
    public function getAllUnverifiedEntries() {
        $query = "SELECT id, student_id, fullname, CONCAT(course, '-', year_level, section) AS course_details, created_at
                  FROM students_unverified_entries";
        return $this->database->fetchAll($query);
    }

    // Fetch a specific entry by ID
    public function getEntryById($entry_id) {
        $query = "SELECT * FROM students_unverified_entries WHERE id = :entry_id";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindValue(':entry_id', $entry_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Move an unverified entry to verified entries
    public function verifyEntry($entry_id) {
        // Start a transaction to ensure consistency
        $this->database->connect()->beginTransaction();

        try {
            // Fetch the unverified entry data
            $entry = $this->getEntryById($entry_id);
            if (!$entry) {
                throw new Exception("Entry not found.");
            }

            // Insert the entry into the verified_entries table
            $query = "INSERT INTO student_verified_entries (student_id, email, fullname, course, year_level, section, adviser_name, gwa, image_proof, created_at)
                      VALUES (:student_id, :email, :fullname, :course, :year_level, :section, :adviser_name, :gwa, :image_proof, :created_at)";
            $stmt = $this->database->connect()->prepare($query);

            // Bind values
            $stmt->bindValue(':student_id', $entry['student_id'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $entry['email'], PDO::PARAM_STR);
            $stmt->bindValue(':fullname', $entry['fullname'], PDO::PARAM_STR);
            $stmt->bindValue(':course', $entry['course'], PDO::PARAM_STR);
            $stmt->bindValue(':year_level', $entry['year_level'], PDO::PARAM_INT);
            $stmt->bindValue(':section', $entry['section'], PDO::PARAM_STR);
            $stmt->bindValue(':adviser_name', $entry['adviser_name'], PDO::PARAM_STR);
            $stmt->bindValue(':gwa', $entry['gwa'], PDO::PARAM_STR);
            $stmt->bindValue(':image_proof', $entry['image_proof'], PDO::PARAM_LOB);
            $stmt->bindValue(':created_at', $entry['created_at'], PDO::PARAM_STR);

            $stmt->execute();

            // Delete the entry from the students_unverified_entries table
            $deleteQuery = "DELETE FROM students_unverified_entries WHERE id = :entry_id";
            $deleteStmt = $this->database->connect()->prepare($deleteQuery);
            $deleteStmt->bindValue(':entry_id', $entry_id, PDO::PARAM_INT);
            $deleteStmt->execute();

            // Commit the transaction
            $this->database->connect()->commit();
            return true;

        } catch (Exception $e) {
            // Roll back the transaction in case of error
            $this->database->connect()->rollBack();
            return false;
        }
    }
}
?>
