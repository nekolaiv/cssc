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

    // Fetch all unverified entries
    public function getAllUnverifiedEntries() {
        $query = "
            SELECT 
                e.id,
                e.student_id,
                e.fullname,
                CONCAT(e.course, '-', e.year_level, e.section) AS course_details,
                e.created_at,
                rs.status
            FROM students_unverified_entries e
            LEFT JOIN registered_students rs ON e.student_id = rs.student_id
        ";
    
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

    // Verify Entry
public function verifyEntry($entry_id) {
    $connection = $this->database->connect();

    try {
        $connection->beginTransaction();

        $entry = $this->getEntryById($entry_id);
        if (!$entry) {
            throw new Exception("Entry with ID $entry_id not found.");
        }

        $query = "INSERT INTO students_verified_entries (student_id, email, fullname, course, year_level, section, adviser_name, gwa, image_proof, created_at)
                  VALUES (:student_id, :email, :fullname, :course, :year_level, :section, :adviser_name, :gwa, :image_proof, :created_at)";
        $stmt = $connection->prepare($query);

        $stmt->execute([
            ':student_id' => $entry['student_id'],
            ':email' => $entry['email'],
            ':fullname' => $entry['fullname'],
            ':course' => $entry['course'],
            ':year_level' => $entry['year_level'],
            ':section' => $entry['section'],
            ':adviser_name' => $entry['adviser_name'],
            ':gwa' => $entry['gwa'],
            ':image_proof' => $entry['image_proof'],
            ':created_at' => $entry['created_at'],
        ]);

        $deleteQuery = "DELETE FROM students_unverified_entries WHERE id = :entry_id";
        $deleteStmt = $connection->prepare($deleteQuery);
        $deleteStmt->execute([':entry_id' => $entry_id]);

        // Update student status
        $updateStatusQuery = "UPDATE registered_students SET status = 'Verified' WHERE student_id = :student_id";
        $statusStmt = $connection->prepare($updateStatusQuery);
        $statusStmt->execute([':student_id' => $entry['student_id']]);

        $connection->commit();
        return true;

    } catch (Exception $e) {
        $connection->rollBack();
        error_log("Error in verifyEntry: " . $e->getMessage());
        return false;
    }
}

// Reject Entry
public function rejectEntry($entry_id) {
    try {
        $entry = $this->getEntryById($entry_id);
        if (!$entry) {
            throw new Exception("Entry with ID $entry_id not found.");
        }

        $updateStatusQuery = "UPDATE registered_students SET status = 'Need Revision' WHERE student_id = :student_id";
        $this->database->execute($updateStatusQuery, [':student_id' => $entry['student_id']]);

        return true;

    } catch (Exception $e) {
        error_log("Error in rejectEntry: " . $e->getMessage());
        return false;
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

        // Insert back into unverified entries
        $query = "INSERT INTO students_unverified_entries 
                  (student_id, email, fullname, course, year_level, section, adviser_name, gwa, image_proof, created_at)
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

        // Update the student's status to Pending
        $updateStatusQuery = "UPDATE registered_students SET status = 'Pending' WHERE student_id = :student_id";
        $updateStatusStmt = $connection->prepare($updateStatusQuery);
        $updateStatusStmt->bindValue(':student_id', $entry['student_id'], PDO::PARAM_STR);
        $updateStatusStmt->execute();

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
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }
        error_log("Error in removeVerifiedEntry: " . $e->getMessage());
        return false;
    }
}


    
    
}
?>
