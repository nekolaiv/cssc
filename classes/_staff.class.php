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
        // Use the same database connection for all operations
        $connection = $this->database->connect();
    
        try {
            // Start a transaction
            $connection->beginTransaction();
    
            // Fetch the unverified entry data
            $entry = $this->getEntryById($entry_id);
            if (!$entry) {
                throw new Exception("Entry with ID $entry_id not found.");
            }
    
            // Insert the entry into the verified_entries table
            $query = "INSERT INTO students_verified_entries (student_id, email, fullname, course, year_level, section, adviser_name, gwa, image_proof, created_at)
                      VALUES (:student_id, :email, :fullname, :course, :year_level, :section, :adviser_name, :gwa, :image_proof, :created_at)";
            $stmt = $connection->prepare($query);
    
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
    
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert the entry into the verified entries table.");
            }
    
            // Delete the entry from the students_unverified_entries table
            $deleteQuery = "DELETE FROM students_unverified_entries WHERE id = :entry_id";
            $deleteStmt = $connection->prepare($deleteQuery);
            $deleteStmt->bindValue(':entry_id', $entry_id, PDO::PARAM_INT);
            if (!$deleteStmt->execute()) {
                throw new Exception("Failed to delete the entry from unverified entries.");
            }
    
            // Commit the transaction
            $connection->commit();
            return "Entry with ID $entry_id successfully verified.";
    
        } catch (Exception $e) {
            // Roll back the transaction in case of error
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
    
            // Return the error message for debugging
            return "Error in verifyEntry: " . $e->getMessage();
        }
    }

    // Fetch all verified entries
public function getAllVerifiedEntries() {
    $query = "SELECT id, student_id, email, fullname, CONCAT(course, '-', year_level, section) AS course_details, gwa, adviser_name, created_at
              FROM students_verified_entries";
    return $this->database->fetchAll($query);
}

// Fetch a specific verified entry by ID
public function getVerifiedEntryById($entry_id) {
    $query = "SELECT * FROM students_verified_entries WHERE id = :entry_id";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->bindValue(':entry_id', $entry_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Remove an entry from verified entries (move back to unverified or delete)
public function removeVerifiedEntry($entry_id) {
    try {
        // Start transaction
        $connection = $this->database->connect();
        $connection->beginTransaction();

        // Fetch the verified entry
        $entry = $this->getVerifiedEntryById($entry_id);
        if (!$entry) {
            throw new Exception("Verified entry with ID $entry_id not found.");
        }

        // Insert back into unverified entries if needed
        $query = "INSERT INTO students_unverified_entries (student_id, email, fullname, course, year_level, section, adviser_name, gwa, image_proof, created_at)
                  VALUES (:student_id, :email, :fullname, :course, :year_level, :section, :adviser_name, :gwa, :image_proof, :created_at)";
        $stmt = $connection->prepare($query);

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

        // Delete the entry from verified entries
        $deleteQuery = "DELETE FROM students_verified_entries WHERE id = :entry_id";
        $deleteStmt = $connection->prepare($deleteQuery);
        $deleteStmt->bindValue(':entry_id', $entry_id, PDO::PARAM_INT);
        $deleteStmt->execute();

        // Commit transaction
        $connection->commit();
        return true;

    } catch (Exception $e) {
        // Rollback in case of error
        $connection->rollBack();
        error_log("Error in removeVerifiedEntry: " . $e->getMessage());
        return false;
    }
}

    
    
}
?>
