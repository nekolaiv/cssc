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
        /**
     * Create a new staff member and their account
     */
    public function createStaff($data) {
        try {
            $conn = $this->database->connect();
            $conn->beginTransaction();

            // Insert into `user` table
            $userSql = "INSERT INTO user (identifier, firstname, middlename, lastname, email, department_id, created_at) 
                        VALUES (:identifier, :firstname, :middlename, :lastname, :email, :department_id, NOW())";
            $stmt = $conn->prepare($userSql);
            $stmt->execute([
                ':identifier' => $data['identifier'],
                ':firstname' => $data['first_name'],
                ':middlename' => $data['middle_name'],
                ':lastname' => $data['last_name'],
                ':email' => $data['email'],
                ':department_id' => $data['department_id']
            ]);

            $userId = $conn->lastInsertId();

            // Insert into `account` table
            $accountSql = "INSERT INTO account (user_id, username, password, role_id, status, created_at) 
                           VALUES (:user_id, :username, :password, 2, :status, NOW())";
            $stmt = $conn->prepare($accountSql);
            $stmt->execute([
                ':user_id' => $userId,
                ':username' => $data['username'],
                ':password' => $data['password'], // Make sure this is hashed before calling
                ':status' => $data['status']
            ]);

            $conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("Database Error in createStaff: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update staff member and account details
     */
    public function updateStaff($data) {
        try {
            $conn = $this->database->connect();
            $conn->beginTransaction();

            // Update `user` table
            $userSql = "UPDATE user 
                        SET identifier = :identifier, firstname = :firstname, middlename = :middlename, 
                            lastname = :lastname, email = :email, department_id = :department_id
                        WHERE id = :id";
            $stmt = $conn->prepare($userSql);
            $stmt->execute([
                ':id' => $data['id'],
                ':identifier' => $data['identifier'],
                ':firstname' => $data['first_name'],
                ':middlename' => $data['middle_name'],
                ':lastname' => $data['last_name'],
                ':email' => $data['email'],
                ':department_id' => $data['department_id']
            ]);

            // Update `account` table
            $accountSql = "UPDATE account 
                           SET username = :username, status = :status";
            $params = [
                ':username' => $data['username'],
                ':status' => $data['status'],
                ':id' => $data['id']
            ];

            if (!empty($data['password'])) {
                $accountSql .= ", password = :password";
                $params[':password'] = $data['password'];
            }

            $accountSql .= " WHERE user_id = :id";
            $stmt = $conn->prepare($accountSql);
            $stmt->execute($params);

            $conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("Database Error in updateStaff: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieve all staff with optional filters
     */
    public function getAllStaff($filters = []) {
        $sql = "SELECT u.id, 
                       u.identifier, 
                       a.username, 
                       CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) AS full_name, 
                       u.email, 
                       d.department_name AS department, 
                       a.status
                FROM user u
                INNER JOIN department d ON u.department_id = d.id
                INNER JOIN account a ON u.id = a.user_id
                WHERE a.role_id = 2"; // Role ID 2 for staff

        $params = [];

        // Apply filters dynamically
        if (!empty($filters['search'])) {
            $sql .= " AND (u.identifier LIKE :search 
                           OR CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) LIKE :search
                           OR a.username LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['department_id'])) {
            $sql .= " AND u.department_id = :department_id";
            $params[':department_id'] = $filters['department_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND a.status = :status";
            $params[':status'] = $filters['status'];
        }

        $sql .= " ORDER BY u.identifier ASC";

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Soft delete a staff member (set inactive status)
     */
    public function softDeleteStaff($id) {
        $sql = "UPDATE account SET status = 'inactive' WHERE user_id = :id";
        $stmt = $this->database->connect()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Check if an identifier exists (used for validation)
     */
    public function identifierExists($identifier, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM user WHERE identifier = :identifier";
        $params = [':identifier' => $identifier];

        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Retrieve a single staff member by ID
     */
    public function getStaffById($id) {
        $sql = "SELECT u.id, 
                       u.identifier, 
                       a.username, 
                       u.firstname, 
                       u.middlename, 
                       u.lastname, 
                       u.email, 
                       u.department_id, 
                       a.status
                FROM user u
                INNER JOIN account a ON u.id = a.user_id
                WHERE u.id = :id";

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDepartments() {
        $sql = "SELECT id, department_name FROM department";
        $stmt = $this->database->connect()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM account WHERE username = :username";
        $params = [':username' => $username];
    
        if ($excludeId !== null) {
            $sql .= " AND user_id != :exclude_id"; // Exclude a specific user ID (for updates)
            $params[':exclude_id'] = $excludeId;
        }
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchColumn() > 0; // Returns true if a username exists, otherwise false
    }
    
    public function departmentExists($departmentId) {
        $sql = "SELECT COUNT(*) FROM department WHERE id = :department_id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([':department_id' => $departmentId]);
    
        return $stmt->fetchColumn() > 0; // Returns true if a department exists, otherwise false
    }

    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM user WHERE email = :email";
        $params = [':email' => $email];
    
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id"; // Exclude a specific user ID (for updates)
            $params[':exclude_id'] = $excludeId;
        }
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchColumn() > 0; // Returns true if the email exists, otherwise false
    }
    
    

    public function updateAccountStatus($id, $status) {
        $sql = "UPDATE account SET status = :status WHERE user_id = :id";
    
        $stmt = $this->database->connect()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }

    public function deleteUser($id) {
        $conn = $this->database->connect();
        try {
            $conn->beginTransaction();
    
            // Delete from `account` table
            $accountSql = "DELETE FROM account WHERE user_id = :id";
            $stmt = $conn->prepare($accountSql);
            $stmt->execute([':id' => $id]);
    
            // Delete from `user` table
            $userSql = "DELETE FROM user WHERE id = :id";
            $stmt = $conn->prepare($userSql);
            $stmt->execute([':id' => $id]);
    
            $conn->commit();
            return true;
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error in deleteUser: " . $e->getMessage());
            return false;
        }
    }
    
    public function createUser($data) {
        try {
            $conn = $this->database->connect();
            $conn->beginTransaction();
    
            // Insert into `user` table
            $userSql = "INSERT INTO user (identifier, firstname, middlename, lastname, email, curriculum_id, created_at) 
                        VALUES (:identifier, :firstname, :middlename, :lastname, :email, :curriculum_id, NOW())";
    
            $stmt = $conn->prepare($userSql);
            $stmt->execute([
                ':identifier' => $data['identifier'],
                ':firstname' => $data['first_name'],
                ':middlename' => $data['middle_name'],
                ':lastname' => $data['last_name'],
                ':email' => $data['email'],
                ':curriculum_id' => $data['curriculum_id']
            ]);
    
            // Check if the user insertion succeeded
            $userId = $conn->lastInsertId();
            if (!$userId) {
                throw new Exception("Failed to retrieve user ID after insert.");
            }
    
            // Insert into `account` table
            $accountSql = "INSERT INTO account (user_id, username, password, role_id, status, created_at) 
                           VALUES (:user_id, :username, :password, :role_id, :status, NOW())";
    
            $stmt = $conn->prepare($accountSql);
            $stmt->execute([
                ':user_id' => $userId,
                ':username' => $data['username'],
                ':password' => $data['password'], // Make sure this is hashed
                ':role_id' => 1, // Assign default role_id (e.g., 1 for 'user')
                ':status' => $data['status']
            ]);
    
            $conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            // Log the error
            error_log("Database Error in createUser: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("General Error in createUser: " . $e->getMessage());
            return false;
        }
    }

    public function updateUser($data) {
        try {
            $conn = $this->database->connect();
            $conn->beginTransaction();
    
            // Update the `user` table
            $userSql = "UPDATE user 
                        SET identifier = :identifier, firstname = :firstname, middlename = :middlename, 
                            lastname = :lastname, email = :email, curriculum_id = :curriculum_id, 
                            department_id = :department_id
                        WHERE id = :id";
    
            $stmt = $conn->prepare($userSql);
            $stmt->execute([
                ':id' => $data['id'],
                ':identifier' => $data['identifier'],
                ':firstname' => $data['first_name'],
                ':middlename' => $data['middle_name'],
                ':lastname' => $data['last_name'],
                ':email' => $data['email'],
                ':curriculum_id' => $data['curriculum_id'],
                ':department_id' => $data['department_id'] ?? null, // Null if department is not provided
            ]);
    
            // Update the `account` table
            $accountSql = "UPDATE account 
                           SET username = :username, status = :status, role_id = :role_id
                           WHERE user_id = :id";
    
            $stmt = $conn->prepare($accountSql);
            $stmt->execute([
                ':id' => $data['id'],
                ':username' => $data['username'],
                ':status' => $data['status'],
                ':role_id' => 1,
            ]);
    
            // Commit the transaction
            $conn->commit();
            return true;
        } catch (PDOException $e) {
            // Rollback the transaction on error
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("Database Error in updateUser: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllUsers($filters = []) {
        $sql = "SELECT u.id, 
                       u.identifier, 
                       a.username, 
                       CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) AS full_name, 
                       u.email, 
                       c.remarks AS curriculum, 
                       a.status
                FROM user u
                LEFT JOIN curriculum c ON u.curriculum_id = c.id
                LEFT JOIN account a ON u.id = a.user_id
                LEFT JOIN role r ON a.role_id = r.id
                WHERE r.name = 'user'"; // Filter by role name 'user'
    
        $params = [];
    
        // Add search filter (identifier, name, or username)
        if (!empty($filters['search'])) {
            $sql .= " AND (u.identifier LIKE :search 
                           OR CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) LIKE :search
                           OR a.username LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }
    
        // Filter by curriculum
        if (!empty($filters['curriculum_id'])) {
            $sql .= " AND u.curriculum_id = :curriculum_id";
            $params[':curriculum_id'] = $filters['curriculum_id'];
        }
    
        // Filter by account status
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = :status";
            $params[':status'] = $filters['status'];
        }
    
        // Order by identifier (default ordering)
        $sql .= " ORDER BY u.identifier ASC";
    
        // Prepare and execute query
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);
    
        // Fetch and return results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

public function verifyPassword($plainPassword, $hashedPassword) {
    return password_verify($plainPassword, $hashedPassword);
}

// Fetch audit logs from the database
public function fetchAuditLogs() {
    try {
        $query = "SELECT timestamp, role, name, action, details FROM audit_logs ORDER BY timestamp DESC";
        return $this->database->fetchAll($query);
    } catch (Exception $e) {
        return ['error' => 'Failed to fetch audit logs: ' . $e->getMessage()];
    }
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

    // Fetch counts for student, staff, and admin accounts
    public function getAccountCounts() {
        $students = $this->database->fetchOne("SELECT COUNT(*) as count FROM account WHERE role_id = 1")['count'] ?? 0;
        $staff = $this->database->fetchOne("SELECT COUNT(*) as count FROM account WHERE role_id = 2")['count'] ?? 0;
        $admins = $this->database->fetchOne("SELECT COUNT(*) as count FROM account WHERE role_id = 3")['count'] ?? 0;

        return ['students' => $students, 'staff' => $staff, 'admins' => $admins];
    }

    // Fetch all assigned advisers
    public function getAdvisers() {
        $sql = "
            SELECT 
                CONCAT(u.firstname, ' ', u.lastname) AS name, 
                u.email, 
                d.department_name AS department
            FROM adviser a
            JOIN user u ON a.user_id = u.id
            JOIN department d ON u.department_id = d.id
            ORDER BY u.lastname ASC
        ";
        return $this->database->fetchAll($sql);
    }
    

    // Fetch the latest audit logs
    public function getAuditLogs() {
        $sql = "
            SELECT 
                a.timestamp, 
                r.name AS role, 
                CONCAT(u.firstname, ' ', u.lastname) AS name, 
                a.action_type AS action, 
                a.action_details AS details
            FROM audit_logs a
            JOIN user u ON a.user_id = u.id
            JOIN role r ON a.role_id = r.id
            ORDER BY a.timestamp DESC
            LIMIT 10
        ";
        return $this->database->fetchAll($sql);
    }

    

    public function getCurriculums() {
        $sql = "SELECT id, remarks FROM curriculum";
        $stmt = $this->database->connect()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserById($id) {
        $sql = "SELECT u.id, u.identifier, a.username,
                       u.firstname, u.middlename, u.lastname, 
                       u.email, u.curriculum_id, a.status
                FROM user u
                LEFT JOIN account a ON u.id = a.user_id
                WHERE u.id = :id";
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function softDeleteUser($id) {
        $sql = "UPDATE account SET status = 'inactive' WHERE user_id = :id";
        $stmt = $this->database->connect()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getAllAdmins($filters = []) {
        $sql = "SELECT u.id, 
                       u.identifier, 
                       a.username, 
                       CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) AS full_name, 
                       u.email, 
                       a.status
                FROM user u
                LEFT JOIN account a ON u.id = a.user_id
                LEFT JOIN role r ON a.role_id = r.id
                WHERE r.name = 'admin'"; // Only fetch admins
    
        $params = [];
    
        // Search filter
        if (!empty($filters['search'])) {
            $sql .= " AND (u.identifier LIKE :search 
                           OR CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) LIKE :search
                           OR a.username LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }
    
        // Filter by status
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = :status";
            $params[':status'] = $filters['status'];
        }
    
        $sql .= " ORDER BY u.identifier ASC";
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createAdmin($data) {
        $conn = $this->database->connect();
        try {
            $conn->beginTransaction();
    
            // Insert into `user` table
            $userSql = "INSERT INTO user (identifier, firstname, middlename, lastname, email, created_at)
                        VALUES (:identifier, :firstname, :middlename, :lastname, :email, NOW())";
            $stmt = $conn->prepare($userSql);
            $stmt->execute([
                ':identifier' => $data['identifier'],
                ':firstname' => $data['first_name'],
                ':middlename' => $data['middle_name'] ?? null, // Nullable
                ':lastname' => $data['last_name'],
                ':email' => $data['email']
            ]);
    
            $userId = $conn->lastInsertId(); // Get the inserted user ID
    
            // Insert into `account` table
            $accountSql = "INSERT INTO account (user_id, username, password, role_id, status, created_at)
                           VALUES (:user_id, :username, :password, :role_id, :status, NOW())";
            $stmt = $conn->prepare($accountSql);
            $stmt->execute([
                ':user_id' => $userId,
                ':username' => $data['username'],
                ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
                ':role_id' => 3, // Assuming '3' is the role ID for Admin
                ':status' => $data['status']
            ]);
    
            $conn->commit();
            return true; // Return true if everything works
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error in createAdmin: " . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    public function updateAdmin($data) {
        $conn = $this->database->connect();
        try {
            $conn->beginTransaction();
    
            // Update `user` table
            $userSql = "UPDATE user 
                        SET identifier = :identifier, 
                            firstname = :firstname, 
                            middlename = :middlename, 
                            lastname = :lastname, 
                            email = :email
                        WHERE id = :id";
            $stmt = $conn->prepare($userSql);
            $stmt->execute([
                ':id' => $data['id'],
                ':identifier' => $data['identifier'],
                ':firstname' => $data['first_name'],
                ':middlename' => $data['middle_name'] ?? null, // Nullable
                ':lastname' => $data['last_name'],
                ':email' => $data['email']
            ]);
    
            // Update `account` table
            $accountSql = "UPDATE account 
                           SET username = :username, 
                               status = :status";
            $params = [
                ':username' => $data['username'],
                ':status' => $data['status'],
                ':user_id' => $data['id']
            ];
    
            // If password is provided, include it in the update
            if (!empty($data['password'])) {
                $accountSql .= ", password = :password";
                $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }
    
            $accountSql .= " WHERE user_id = :user_id";
            $stmt = $conn->prepare($accountSql);
            $stmt->execute($params);
    
            $conn->commit();
            return true;
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error in updateAdmin: " . $e->getMessage());
            return false;
        }
    }

    public function getAdminById($id) {
        $sql = "SELECT u.id, 
                       u.identifier, 
                       a.username, 
                       CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) AS full_name, 
                       u.firstname, 
                       u.middlename, 
                       u.lastname, 
                       u.email, 
                       a.status
                FROM user u
                LEFT JOIN account a ON u.id = a.user_id
                LEFT JOIN role r ON a.role_id = r.id
                WHERE u.id = :id AND r.name = 'admin'"; // Only fetch admins
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([':id' => $id]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteAdmin($id) {
        $conn = $this->database->connect();
        try {
            $conn->beginTransaction();
    
            // Delete from `account` table
            $accountSql = "DELETE FROM account WHERE user_id = :id";
            $stmt = $conn->prepare($accountSql);
            $stmt->execute([':id' => $id]);
    
            // Delete from `user` table
            $userSql = "DELETE FROM user WHERE id = :id";
            $stmt = $conn->prepare($userSql);
            $stmt->execute([':id' => $id]);
    
            $conn->commit();
            return true;
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error in deleteAdmin: " . $e->getMessage());
            return false;
        }
    }

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
    
      /**
     * Fetch grades for an application.
     */
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
    
    /**
     * Fetch proof image for a specific application.
     */
    public function getProofImageByApplication($applicationId) {
        $sql = "SELECT image_proof
                FROM student_applications
                WHERE id = :application_id";
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([':application_id' => $applicationId]);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['image_proof'] ?? null;
    }

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

    public function getAllPeriods($filters) {
        $sql = "SELECT id, year, semester, start_date, end_date, status
                FROM dean_lister_application_periods
                WHERE 1=1";
    
        $params = [];
    
        if (!empty($filters['search'])) {
            $sql .= " AND year LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
    
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function autoActivateDeactivatePeriods() {
        $now = date('Y-m-d H:i:s');
    
        // Deactivate periods where the end date has passed
        $sqlDeactivate = "UPDATE dean_lister_application_periods
                          SET status = 'closed'
                          WHERE end_date < :now AND status = 'open'";
        $stmtDeactivate = $this->database->connect()->prepare($sqlDeactivate);
        $stmtDeactivate->execute([':now' => $now]);
    
        // Activate periods where the current date is between start_date and end_date
        $sqlActivate = "UPDATE dean_lister_application_periods
                        SET status = 'open'
                        WHERE start_date <= :now AND end_date >= :now";
        $stmtActivate = $this->database->connect()->prepare($sqlActivate);
        $stmtActivate->execute([':now' => $now]);
    
        // Ensure only one period is active at a time
        $sqlCheck = "SELECT id FROM dean_lister_application_periods
                     WHERE status = 'open' ORDER BY start_date ASC";
        $stmtCheck = $this->database->connect()->prepare($sqlCheck);
        $stmtCheck->execute();
        $activePeriods = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);
    
        if (count($activePeriods) > 1) {
            // Deactivate all but the first active period
            $idsToClose = array_column(array_slice($activePeriods, 1), 'id');
            $sqlDeactivateExtra = "UPDATE dean_lister_application_periods
                                   SET status = 'closed'
                                   WHERE id IN (" . implode(',', $idsToClose) . ")";
            $stmtDeactivateExtra = $this->database->connect()->prepare($sqlDeactivateExtra);
            $stmtDeactivateExtra->execute();
        }
    
        return true;
    }

    public function deactivateAllPeriodsExcept($id) {
        $sql = "UPDATE dean_lister_application_periods
                SET status = 'closed'
                WHERE id != :id AND status = 'open'";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    
    
    
    
    public function createPeriod($data) {
        $sql = "INSERT INTO dean_lister_application_periods (year, semester, start_date, end_date, status)
                VALUES (:year, :semester, :start_date, :end_date, :status)";
    
        $stmt = $this->database->connect()->prepare($sql);
        return $stmt->execute([
            ':year' => $data['year'],
            ':semester' => $data['semester'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':status' => $data['status']
        ]);
    }
    
    public function updatePeriod($id, $data) {
        $sql = "UPDATE dean_lister_application_periods
                SET year = :year, 
                    semester = :semester, 
                    start_date = :start_date, 
                    end_date = :end_date, 
                    status = :status
                WHERE id = :id";
    
        $stmt = $this->database->connect()->prepare($sql);
        return $stmt->execute([
            ':year' => $data['year'],
            ':semester' => $data['semester'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }
    

    public function togglePeriodStatus($id) {
        // Deactivate any currently active period
        $sqlDeactivate = "UPDATE dean_lister_application_periods
                          SET status = 'closed'
                          WHERE status = 'open'";
        $stmtDeactivate = $this->database->connect()->prepare($sqlDeactivate);
        $stmtDeactivate->execute();
    
        // Activate the selected period
        $sqlActivate = "UPDATE dean_lister_application_periods
                        SET status = 'open'
                        WHERE id = :id";
        $stmtActivate = $this->database->connect()->prepare($sqlActivate);
        return $stmtActivate->execute([':id' => $id]);
    }

    public function getAllCurricula($filters) {
        $sql = "SELECT c.id, c.effective_year, c.version, c.remarks, co.course_name AS course_name
                FROM curriculum c
                INNER JOIN course co ON c.course_id = co.id
                WHERE 1=1";
    
        $params = [];
    
        if (!empty($filters['course_id'])) {
            $sql .= " AND c.course_id = :course_id";
            $params[':course_id'] = $filters['course_id'];
        }
        if (!empty($filters['year'])) {
            $sql .= " AND c.effective_year LIKE :year";
            $params[':year'] = '%' . $filters['year'] . '%';
        }
        if (!empty($filters['remarks'])) {
            $sql .= " AND c.remarks LIKE :remarks";
            $params[':remarks'] = '%' . $filters['remarks'] . '%';
        }
        if (!empty($filters['version'])) {
            $sql .= " AND c.version LIKE :version";
            $params[':version'] = '%' . $filters['version'] . '%';
        }
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllCourses() {
        $sql = "SELECT id, course_name FROM course";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createCurriculum($data) {
        $sql = "INSERT INTO curriculum (effective_year, version, remarks, course_id)
                VALUES (:effective_year, :version, :remarks, :course_id)";
    
        $stmt = $this->database->connect()->prepare($sql);
        return $stmt->execute([
            ':effective_year' => $data['effective_year'],
            ':version' => $data['version'],
            ':remarks' => $data['remarks'],
            ':course_id' => $data['course_id']
        ]);
    }
    
    public function updateCurriculum($id, $data) {
        $sql = "UPDATE curriculum
                SET effective_year = :effective_year, 
                    version = :version, 
                    remarks = :remarks, 
                    course_id = :course_id
                WHERE id = :id";
    
        $stmt = $this->database->connect()->prepare($sql);
        return $stmt->execute([
            ':effective_year' => $data['effective_year'],
            ':version' => $data['version'],
            ':remarks' => $data['remarks'],
            ':course_id' => $data['course_id'],
            ':id' => $id
        ]);
    }
    
    public function getCurriculumById($id) {
        $sql = "SELECT id, effective_year, version, remarks, course_id
                FROM curriculum
                WHERE id = :id";
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([':id' => $id]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function deleteCurriculum($id) {
        $sql = "DELETE FROM curriculum WHERE id = :id";
        $stmt = $this->database->connect()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function checkDuplicateCurriculum($course_id, $version, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM curriculum WHERE course_id = :course_id AND version = :version";
        $params = [
            ':course_id' => $course_id,
            ':version' => $version
        ];
    
        if ($exclude_id) {
            $sql .= " AND id != :id";
            $params[':id'] = $exclude_id;
        }
    
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchColumn() > 0; // Returns true if duplicate exists
    }
    
    
    
    
    
    
    
}
?>