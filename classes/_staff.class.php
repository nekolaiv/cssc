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

     /**
     * Get Count of Applications by Status
     */
    public function getApplicationsCountByStatus($status) {
        $sql = "SELECT COUNT(*) as count FROM student_applications WHERE status = :status";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([':status' => $status]);
        return $stmt->fetchColumn();
    }

    /**
     * Get Audit Logs
     */
    public function getAuditLogs() {
        $sql = "SELECT al.timestamp, r.name, al.action_type, al.action_details
                FROM audit_logs al
                INNER JOIN role r ON al.role_id = r.id
                ORDER BY al.timestamp DESC";
        $stmt = $this->database->connect()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     // Fetch all applications with optional filters
     public function getAllApplications($filters) {
        $sql = "SELECT sa.id, 
                       sa.user_id,
                       u.identifier,
                       CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) AS full_name,
                       u.email,
                       c.remarks AS curriculum,
                       sa.status,
                       sa.total_rating,
                       sa.rejection_reason,
                       sa.created_at AS submission_date,
                       sa.updated_at AS last_updated,
                       sa.school_year,
                       sa.semester
                FROM student_applications sa
                INNER JOIN user u ON sa.user_id = u.id
                LEFT JOIN curriculum c ON u.curriculum_id = c.id
                WHERE 1=1";
    
        $params = [];
    
        if (!empty($filters['search'])) {
            $sql .= " AND (u.identifier LIKE :search OR CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['curriculum_id'])) {
            $sql .= " AND u.curriculum_id = :curriculum_id";
            $params[':curriculum_id'] = $filters['curriculum_id'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND sa.status = :status";
            $params[':status'] = $filters['status'];
        }
        if (!empty($filters['submission_date'])) {
            $sql .= " AND DATE(sa.created_at) = :submission_date";
            $params[':submission_date'] = $filters['submission_date'];
        }
        if (!empty($filters['school_year'])) {
            $sql .= " AND sa.school_year = :school_year";
            $params[':school_year'] = $filters['school_year'];
        }
        if (!empty($filters['semester'])) {
            $sql .= " AND sa.semester = :semester";
            $params[':semester'] = $filters['semester'];
        }
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Get application by ID
    public function getApplicationById($id) {
        $sql = "SELECT sa.id, 
                       sa.user_id,
                       u.identifier,
                       CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) AS full_name,
                       u.email,
                       c.remarks AS curriculum,
                       sa.status,
                       sa.total_rating,
                       sa.rejection_reason,
                       sa.created_at AS submission_date,
                       sa.updated_at AS last_updated
                FROM student_applications sa
                INNER JOIN user u ON sa.user_id = u.id
                LEFT JOIN curriculum c ON u.curriculum_id = c.id
                WHERE sa.id = :id";
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([':id' => $id]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch curriculums
    public function getCurriculums() {
        $sql = "SELECT id, remarks FROM curriculum";
        $stmt = $this->database->connect()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch grades by application ID and user ID
    public function getGradesByApplication($applicationId, $userId) {
        $sql = "SELECT r.subject_id,
                       p.subject_code,
                       p.descriptive_title,
                       r.rating
                FROM rating r
                INNER JOIN prospectus p ON r.subject_id = p.id
                WHERE r.application_id = :application_id
                  AND r.user_id = :user_id";
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([
            ':application_id' => $applicationId,
            ':user_id' => $userId
        ]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch proof image by application ID
    public function getProofImageByApplication($applicationId) {
        $sql = "SELECT image_proof
                FROM student_applications
                WHERE id = :application_id";
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([':application_id' => $applicationId]);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['image_proof'] ?? null;
    }

    // Update application status
    public function updateApplicationStatus($applicationId, $newStatus) {
        $sql = "UPDATE student_applications
                SET status = :new_status, updated_at = CURRENT_TIMESTAMP
                WHERE id = :application_id";
    
        $stmt = $this->database->connect()->prepare($sql);
        return $stmt->execute([
            ':new_status' => $newStatus,
            ':application_id' => $applicationId
        ]);
    }

    public function getRecentlyVerifiedApplications() {
        $stmt = $this->database->connect()->prepare("
            SELECT
                u.identifier AS student_identifier,
                CONCAT(u.firstname, ' ', u.lastname) AS full_name,
                c.course_name,
                sa.total_rating AS gwa,
                sa.updated_at AS date_verified
            FROM
                student_applications sa
            INNER JOIN user u ON sa.user_id = u.id
            INNER JOIN course c ON u.curriculum_id = c.id
            WHERE
                sa.status = 'Approved'
            ORDER BY
                sa.updated_at DESC
            LIMIT 5
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
?>
