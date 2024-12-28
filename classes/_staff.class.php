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
    /**
 * Fetch grades for a specific application and user.
 */
public function getGradesByApplication($applicationId, $userId) {
    $sql = "
        SELECT r.subject_id,
               p.subject_code,
               p.descriptive_title,
               r.rating
        FROM rating r
        INNER JOIN prospectus p ON r.subject_id = p.id
        WHERE r.application_id = :application_id
          AND r.user_id = :user_id
    ";

    // Prepare and execute the query
    $stmt = $this->database->connect()->prepare($sql);
    $stmt->execute([
        ':application_id' => $applicationId,
        ':user_id' => $userId
    ]);

    // Fetch and return results
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fetch proof image for a specific application.
 */
public function getProofImageByApplication($applicationId) {
    $sql = "
        SELECT image_proof
        FROM student_applications
        WHERE id = :application_id
    ";

    // Prepare and execute the query
    $stmt = $this->database->connect()->prepare($sql);
    $stmt->execute([':application_id' => $applicationId]);

    // Fetch the BLOB data
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Convert BLOB to Base64 if data exists
    if (!empty($result['image_proof'])) {
        $base64Image = base64_encode($result['image_proof']);
        return "data:image/jpeg;base64," . $base64Image; // Assuming JPEG format
    }

    // Return null if no image data found
    return null;
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

    public function getAllAuditLogs($filters) {
        $sql = "SELECT al.id, al.user_id, r.name, al.action_type, al.action_details, al.timestamp
                FROM audit_logs al
                LEFT JOIN role r ON al.role_id = r.id";
    
        $conditions = [];
        $params = [];
    
        if (!empty($filters['role_id'])) {
            $conditions[] = "al.role_id = :role_id";
            $params[':role_id'] = $filters['role_id'];
        }
    
        if ($conditions) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
    
        $sql .= " ORDER BY al.timestamp DESC";
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRoles() {
        $sql = "SELECT id, name as role_name FROM role";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteAuditLog($id) {
        $sql = "DELETE FROM audit_logs WHERE id = :id";
        $stmt = $this->database->connect()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Log an audit event
    public function logAudit($actionType, $actionDetails) {
        try {
            if (isset($_SESSION['user-id']) && isset($_SESSION['user-role'])) {
                $userId = $_SESSION['user-id'];
                $roleId = $this->getRoleIdByName($_SESSION['user-role']);
    
                // Verify that the user_id exists in the user table
                $sqlCheckUser = "SELECT id FROM user WHERE id = :user_id";
                $stmtCheckUser = $this->database->connect()->prepare($sqlCheckUser);
                $stmtCheckUser->bindValue(':user_id', $userId, PDO::PARAM_INT);
                $stmtCheckUser->execute();
                $userExists = $stmtCheckUser->fetch(PDO::FETCH_ASSOC);
    
                if (!$userExists) {
                    error_log("logAudit: user_id $userId does not exist in the user table.");
                    return false;
                }
    
                // Insert into audit_logs
                $sql = "INSERT INTO audit_logs (user_id, role_id, action_type, action_details, timestamp) 
                        VALUES (:user_id, :role_id, :action_type, :action_details, NOW())";
    
                $stmt = $this->database->connect()->prepare($sql);
                $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindValue(':role_id', $roleId, PDO::PARAM_INT);
                $stmt->bindValue(':action_type', $actionType, PDO::PARAM_STR);
                $stmt->bindValue(':action_details', $actionDetails, PDO::PARAM_STR);
    
                if ($stmt->execute()) {
                    return true;
                } else {
                    error_log('logAudit: Query execution failed.');
                    return false;
                }
            } else {
                error_log('logAudit: Required session variables are not set.');
                return false;
            }
        } catch (Exception $e) {
            error_log('logAudit: Exception occurred - ' . $e->getMessage());
            return false;
        }
    }
    

// Helper function to map role name to role ID
private function getRoleIdByName($roleName) {
    $roles = [
        'admin' => 3,
        'staff' => 2,
        'user' => 1
    ];
    if (!isset($roles[$roleName])) {
        error_log('getRoleIdByName: Invalid role name: ' . $roleName);
    }
    return $roles[$roleName] ?? null;
}

 /**
     * Get Staff Profile
     * @param int $staffId
     * @return array|false
     */
    public function getStaffProfile($staffId) {
        try {
            $sql = "
                SELECT 
                    u.identifier, u.firstname, u.middlename, u.lastname, u.email,
                    a.username, d.department_name
                FROM user u
                JOIN account a ON u.id = a.user_id
                JOIN department d ON u.department_id = d.id
                WHERE u.id = :staff_id
            ";
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->bindValue(':staff_id', $staffId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$result) {
                error_log('getStaffProfile: No results found for staff ID ' . $staffId);
            }
    
            return $result;
        } catch (Exception $e) {
            error_log('Error in getStaffProfile: ' . $e->getMessage());
            return false;
        }
    }
    

    /**
     * Update Staff Profile
     * @param int $staffId
     * @param array $data
     * @return bool
     */
    public function updateStaffProfile($staffId, $data) {
        try {
            $sql = "
                UPDATE user u
                JOIN account a ON u.id = a.user_id
                SET 
                    u.identifier = :identifier,
                    u.firstname = :firstname,
                    u.middlename = :middlename,
                    u.lastname = :lastname,
                    u.email = :email,
                    u.department_id = :department_id,
                    a.username = :username
                WHERE u.id = :staff_id
            ";
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->bindValue(':identifier', $data['identifier'], PDO::PARAM_STR);
            $stmt->bindValue(':firstname', $data['firstname'], PDO::PARAM_STR);
            $stmt->bindValue(':middlename', $data['middlename'], PDO::PARAM_STR);
            $stmt->bindValue(':lastname', $data['lastname'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':department_id', $data['department_id'], PDO::PARAM_INT);
            $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindValue(':staff_id', $staffId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log('Error in updateStaffProfile: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get Departments
     * @return array|false
     */
    public function getDepartments() {
        try {
            $sql = "SELECT id, department_name FROM department";
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Error in getDepartments: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Change Staff Password
     * @param int $staffId
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     */
    public function changeStaffPassword($staffId, $currentPassword, $newPassword) {
        try {
            $sql = "SELECT a.password FROM account a WHERE a.user_id = :staff_id";
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->bindValue(':staff_id', $staffId, PDO::PARAM_INT);
            $stmt->execute();
            $currentHash = $stmt->fetchColumn();

            if (!$currentHash || !password_verify($currentPassword, $currentHash)) {
                error_log('changeStaffPassword: Incorrect current password.');
                return false;
            }

            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateSql = "UPDATE account SET password = :new_password WHERE user_id = :staff_id";
            $updateStmt = $this->database->connect()->prepare($updateSql);
            $updateStmt->bindValue(':new_password', $newHash, PDO::PARAM_STR);
            $updateStmt->bindValue(':staff_id', $staffId, PDO::PARAM_INT);
            return $updateStmt->execute();
        } catch (Exception $e) {
            error_log('Error in changeStaffPassword: ' . $e->getMessage());
            return false;
        }
    }

    
    
}
?>
