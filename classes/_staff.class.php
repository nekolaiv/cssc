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
            LEFT JOIN student_accounts rs ON e.student_id = rs.student_id
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

        $query = "INSERT INTO students_verified_entries (student_id, email, fullname, course, year_level, section, gwa, image_proof, created_at)
                  VALUES (:student_id, :email, :fullname, :course, :year_level, :section, :gwa, :image_proof, :created_at)";
        $stmt = $connection->prepare($query);

        $stmt->execute([
            ':student_id' => $entry['student_id'],
            ':email' => $entry['email'],
            ':fullname' => $entry['fullname'],
            ':course' => $entry['course'],
            ':year_level' => $entry['year_level'],
            ':section' => $entry['section'],
            ':gwa' => $entry['gwa'],
            ':image_proof' => $entry['image_proof'],
            ':created_at' => $entry['created_at'],
        ]);

        $deleteQuery = "DELETE FROM students_unverified_entries WHERE id = :entry_id";
        $deleteStmt = $connection->prepare($deleteQuery);
        $deleteStmt->execute([':entry_id' => $entry_id]);

        // Update student status
        $updateStatusQuery = "UPDATE student_accounts SET status = 'Verified' WHERE student_id = :student_id";
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

        $updateStatusQuery = "UPDATE student_accounts SET status = 'Need Revision' WHERE student_id = :student_id";
        $this->database->execute($updateStatusQuery, [':student_id' => $entry['student_id']]);

        return true;

    } catch (Exception $e) {
        error_log("Error in rejectEntry: " . $e->getMessage());
        return false;
    }
}


    // Fetch all verified entries
public function getAllVerifiedEntries() {
    $query = "SELECT id, student_id, email, fullname, CONCAT(course, '-', year_level, section) AS course_details, gwa, created_at
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


// Log an audit event
public function logAudit($action, $details) {
    if (isset($_SESSION['profile']) && isset($_SESSION['user-type'])) {
        $role = strtoupper($_SESSION['user-type']);
        $name = $_SESSION['profile']['fullname'];
        $sql = "INSERT INTO audit_logs (timestamp, role, name, action, details) 
                VALUES (NOW(), :role, :name, :action, :details)";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':action', $action, PDO::PARAM_STR);
        $stmt->bindValue(':details', $details, PDO::PARAM_STR);
        return $stmt->execute();
    }
    return false;
}


public function getEntryWithStatus($entry_id) {
    $query = "SELECT * FROM students_unverified_entries WHERE id = :entry_id";
    $entry = $this->database->fetchOne($query, [':entry_id' => $entry_id]);

    if ($entry) {
        $statusQuery = "SELECT status FROM student_accounts WHERE student_id = :student_id";
        $status = $this->database->fetchOne($statusQuery, [':student_id' => $entry['student_id']]);
        $entry['status'] = $status['status'] ?? 'Not Submitted';

        // If there's an image, encode it properly
        if (!empty($entry['image_proof'])) {
            $entry['image_proof'] = base64_encode($entry['image_proof']);
        }
    }

    return $entry ?: ['error' => 'Entry not found.'];
}

public function verifyAndLogEntry($entry_id) {
    try {
        $this->database->connect()->beginTransaction();

        // Fetch entry details
        $entryDetails = $this->getEntryById($entry_id);
        if (!$entryDetails) {
            return ['success' => false, 'error' => 'Entry not found.'];
        }

        $studentId = $entryDetails['student_id'];

        // Move entry to verified table
        $insertQuery = "
            INSERT INTO students_verified_entries (student_id, email, fullname, course, year_level, section, gwa, image_proof, submission_id, created_at, updated_at)
            SELECT student_id, email, fullname, course, year_level, section, gwa, image_proof, submission_id, created_at, updated_at
            FROM students_unverified_entries
            WHERE id = :entry_id
        ";
        $this->database->execute($insertQuery, [':entry_id' => $entry_id]);

        // Delete from unverified table
        $deleteQuery = "DELETE FROM students_unverified_entries WHERE id = :entry_id";
        $this->database->execute($deleteQuery, [':entry_id' => $entry_id]);

        // Log audit
        $this->logAudit('Verify Entry', "Verified entry for Student ID: $studentId");

        $this->database->connect()->commit();
        return ['success' => true, 'message' => 'Entry verified successfully.'];
    } catch (Exception $e) {
        $this->database->connect()->rollBack();
        return ['success' => false, 'error' => 'Failed to verify the entry.'];
    }
}

public function rejectAndLogEntry($entry_id) {
    try {
        // Fetch entry details
        $entry = $this->getEntryById($entry_id);
        if (!$entry) {
            throw new Exception("Entry with ID $entry_id not found.");
        }

        // Update the status in the student_accounts table
        $updateStatusQuery = "UPDATE student_accounts SET status = 'Need Revision' WHERE student_id = :student_id";
        $this->database->execute($updateStatusQuery, [':student_id' => $entry['student_id']]);

        // Log audit
        $this->logAudit('Reject Entry', "Marked Student ID: {$entry['student_id']} as 'Need Revision'.");

        return ['success' => true, 'message' => 'Entry marked as "Need Revision" successfully.'];

    } catch (Exception $e) {
        error_log("Error in rejectAndLogEntry: " . $e->getMessage());
        return ['success' => false, 'error' => 'Failed to reject the entry.'];
    }
}

public function getSubjectFieldsByStudent($student_id) {
    $query = "
        SELECT subject_code, units, grade, academic_year, semester
        FROM subject_fields
        WHERE student_id = :student_id
    ";
    return $this->database->fetchAll($query, [':student_id' => $student_id]);
}    


public function getVerifiedEntryWithDetails($entry_id) {
    $query = "SELECT * FROM students_verified_entries WHERE id = :entry_id";
    $entry = $this->database->fetchOne($query, [':entry_id' => $entry_id]);

    if ($entry) {
        // If there's an image, encode it properly
        if (!empty($entry['image_proof'])) {
            $entry['image_proof'] = base64_encode($entry['image_proof']);
        }
        return ['success' => true, 'entry' => $entry];
    } else {
        return ['success' => false, 'error' => 'Entry not found.'];
    }
}

public function removeAndLogVerifiedEntry($entry_id) {
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
                  (student_id, email, fullname, course, year_level, section, gwa, image_proof, created_at)
                  VALUES (:student_id, :email, :fullname, :course, :year_level, :section, :gwa, :image_proof, :created_at)";
        $stmt = $connection->prepare($query);

        // Bind values
        $stmt->bindValue(':student_id', $entry['student_id'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $entry['email'], PDO::PARAM_STR);
        $stmt->bindValue(':fullname', $entry['fullname'], PDO::PARAM_STR);
        $stmt->bindValue(':course', $entry['course'], PDO::PARAM_STR);
        $stmt->bindValue(':year_level', $entry['year_level'], PDO::PARAM_INT);
        $stmt->bindValue(':section', $entry['section'], PDO::PARAM_STR);
        $stmt->bindValue(':gwa', $entry['gwa'], PDO::PARAM_STR);
        $stmt->bindValue(':image_proof', $entry['image_proof'], PDO::PARAM_LOB);
        $stmt->bindValue(':created_at', $entry['created_at'], PDO::PARAM_STR);

        $stmt->execute();

        // Update the student's status to Pending
        $updateStatusQuery = "UPDATE student_accounts SET status = 'Pending' WHERE student_id = :student_id";
        $updateStatusStmt = $connection->prepare($updateStatusQuery);
        $updateStatusStmt->bindValue(':student_id', $entry['student_id'], PDO::PARAM_STR);
        $updateStatusStmt->execute();

        // Delete the entry from verified entries
        $deleteQuery = "DELETE FROM students_verified_entries WHERE id = :entry_id";
        $deleteStmt = $connection->prepare($deleteQuery);
        $deleteStmt->bindValue(':entry_id', $entry_id, PDO::PARAM_INT);
        $deleteStmt->execute();

        // Log the audit
        $this->logAudit('Remove Verified Entry', "Moved entry for Student ID: {$entry['student_id']} back to unverified entries and marked status as Pending.");

        // Commit transaction
        $connection->commit();
        return ['success' => true, 'message' => 'Entry removed and moved back to unverified entries successfully.'];

    } catch (Exception $e) {
        // Rollback in case of error
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }
        error_log("Error in removeAndLogVerifiedEntry: " . $e->getMessage());
        return ['success' => false, 'error' => 'Failed to remove and move the entry back to unverified entries.'];
    }
}
}
?>
