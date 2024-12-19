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
    public function createAdmin($data) {
        $sql = "INSERT INTO admin_accounts (email, password, first_name, last_name, middle_name) VALUES (:email, :password, :first_name, :last_name, :middle_name)";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(':password', $data['password'], PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $data['first_name'], PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $data['last_name'], PDO::PARAM_STR);
        $stmt->bindValue(':middle_name', $data['middle_name'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Get All Admins
    public function getAllAdmins() {
        $sql = "SELECT admin_id, email, password, first_name, last_name, middle_name FROM admin_accounts";
        return $this->database->fetchAll($sql);
    }

    // Get Admin By ID
    public function getAdminById($admin_id) {
        $sql = "SELECT admin_id, email, first_name, last_name, middle_name FROM admin_accounts WHERE admin_id = :admin_id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update Admin
    public function updateAdmin($data) {
        $query = "UPDATE admin_accounts SET email = :email, first_name = :first_name, last_name = :last_name, middle_name = :middle_name";
        
        if (!empty($data['password'])) {
            $query .= ", password = :password";
        }
        
        $query .= " WHERE admin_id = :admin_id";

        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':first_name', $data['first_name']);
        $stmt->bindValue(':last_name', $data['last_name']);
        $stmt->bindValue(':middle_name', $data['middle_name']);
        if (!empty($data['password'])) {
            $stmt->bindValue(':password', $data['password']);
        }
        $stmt->bindValue(':admin_id', $data['admin_id']);
        return $stmt->execute();
    }

    // Delete Admin
    public function deleteAdmin($admin_id) {
        $sql = "DELETE FROM admin_accounts WHERE admin_id = :admin_id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Check if Email Exists
    public function emailExists($email, $exclude_admin_id = null) {
        $sql = "SELECT admin_id FROM admin_accounts WHERE email = :email";
        if ($exclude_admin_id) {
            $sql .= " AND admin_id != :admin_id";
        }

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        if ($exclude_admin_id) {
            $stmt->bindValue(':admin_id', $exclude_admin_id, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchColumn() ? true : false;
    }

        // Create a new staff account
        public function createStaff($data) {
            $query = "INSERT INTO staff_accounts (email, password, first_name, last_name, middle_name) VALUES (:email, :password, :first_name, :last_name, :middle_name)";
            $params = [
                ':email' => $data['email'],
                ':password' => $data['password'],
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':middle_name' => $data['middle_name']
            ];
    
            return $this->database->execute($query, $params);
        }
        
    // Get all staff accounts
    public function getAllStaff() {
        $query = "SELECT staff_id, email, password, first_name, last_name, middle_name FROM staff_accounts";
        return $this->database->fetchAll($query);
    }

    // Get a specific staff account by ID
    public function getStaffById($staff_id) {
        $query = "SELECT staff_id, email, password, first_name, last_name, middle_name FROM staff_accounts WHERE staff_id = :staff_id";
        $params = [':staff_id' => $staff_id];
        return $this->database->fetch($query, $params);
    }

    // Update an existing staff account
    public function updateStaff($data) {
        $query = "UPDATE staff_accounts SET email = :email, first_name = :first_name, last_name = :last_name, middle_name = :middle_name";

        $params = [
            ':email' => $data['email'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':middle_name' => $data['middle_name'],
            ':staff_id' => $data['staff_id']
        ];

        if (isset($data['password']) && !empty($data['password'])) {
            $query .= ", password = :password";
            $params[':password'] = $data['password'];
        }

        $query .= " WHERE staff_id = :staff_id";

        return $this->database->execute($query, $params);
    }

    // Delete a staff account
    public function deleteStaff($staff_id) {
        $query = "DELETE FROM staff_accounts WHERE staff_id = :staff_id";
        $params = [':staff_id' => $staff_id];
        return $this->database->execute($query, $params);
    }

    // Check if staff email exists (for both create and update)
    public function staffEmailExists($email, $excludeId = null) {
        $query = "SELECT COUNT(*) FROM staff_accounts WHERE email = :email";
        $params = [':email' => $email];

        if ($excludeId !== null) {
            $query .= " AND staff_id != :staff_id";
            $params[':staff_id'] = $excludeId;
        }

        return $this->database->fetchColumn($query, $params) > 0;
    }

    public function getNextUserId() {
        // Fetch the highest user_id from the student_accounts table
        $query = "SELECT MAX(user_id) AS max_user_id FROM student_accounts";
        $result = $this->database->fetchOne($query);
    
        // If no rows exist, start from 1; otherwise, increment the max_user_id
        return ($result && $result['max_user_id']) ? $result['max_user_id'] + 1 : 1;
    }

    public function createStudent($data) {
        // Determine the next user_id
        $data['user_id'] = $this->getNextUserId();
    
        // SQL query to insert a new student
        $query = "INSERT INTO student_accounts (
                    user_id, student_id, email, password, first_name, last_name, middle_name, 
                    course_id, year_level, section, curriculum_code, role
                  ) VALUES (
                    :user_id, :student_id, :email, :password, :first_name, :last_name, :middle_name, 
                    :course_id, :year_level, :section, :curriculum_code, 'student'
                  )";
    
        $stmt = $this->database->connect()->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':student_id', $data['student_id']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':middle_name', $data['middle_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':course_id', $data['course_id']);
        $stmt->bindParam(':year_level', $data['year_level']);
        $stmt->bindParam(':section', $data['section']);
        $stmt->bindParam(':curriculum_code', $data['curriculum_code']);
    
        return $stmt->execute();
    }
    
    
    public function getAllStudents($filters = [])
{
    $query = "SELECT sa.*, c.course_code 
              FROM student_accounts sa
              LEFT JOIN courses c ON sa.course_id = c.course_id";

    $params = [];
    $conditions = [];

    // Apply filters dynamically
    if (!empty($filters['course_id'])) {
        $conditions[] = "sa.course_id = :course_id";
        $params[':course_id'] = $filters['course_id'];
    }
    if (!empty($filters['year_level'])) {
        $conditions[] = "sa.year_level = :year_level";
        $params[':year_level'] = $filters['year_level'];
    }
    if (!empty($filters['section'])) {
        $conditions[] = "sa.section = :section";
        $params[':section'] = $filters['section'];
    }

    // Append conditions to query
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $query .= " ORDER BY sa.student_id";

    // Execute query
    $stmt = $this->database->connect()->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function updateStudent($data) {
        $query = "UPDATE student_accounts SET 
                    student_id = :student_id, 
                    email = :email, 
                    first_name = :first_name, 
                    middle_name = :middle_name, 
                    last_name = :last_name, 
                    course_id = :course_id, 
                    year_level = :year_level, 
                    section = :section, 
                    curriculum_code = :curriculum_code";

        // Include password update only if provided
        if (!empty($data['password'])) {
            $query .= ", password = :password";
        }

        $query .= " WHERE user_id = :user_id";

        $stmt = $this->database->connect()->prepare($query);

        // Bind parameters
        $stmt->bindParam(':student_id', $data['student_id']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':middle_name', $data['middle_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':course_id', $data['course_id']);
        $stmt->bindParam(':year_level', $data['year_level']);
        $stmt->bindParam(':section', $data['section']);
        $stmt->bindParam(':curriculum_code', $data['curriculum_code']);
        $stmt->bindParam(':user_id', $data['user_id']);

        // Bind password if provided
        if (!empty($data['password'])) {
            $stmt->bindParam(':password', $data['password']);
        }

        return $stmt->execute();
    }

    

    public function deleteStudent($user_id) {
        $query = "DELETE FROM student_accounts WHERE user_id = :user_id";
        return $this->database->execute($query, ['user_id' => $user_id]);
    }

    public function getStudentById($user_id) {
        $query = "
            SELECT 
                sa.*, 
                c.course_code 
            FROM 
                student_accounts sa
            LEFT JOIN 
                courses c ON sa.course_id = c.course_id
            WHERE 
                sa.user_id = :user_id 
            LIMIT 1
        ";
        return $this->database->fetchOne($query, ['user_id' => $user_id]);
    }
    

    public function studentIdExists($student_id, $exclude_user_id = null) {
        $query = "SELECT COUNT(*) FROM student_accounts WHERE student_id = :student_id";
        if ($exclude_user_id) {
            $query .= " AND user_id != :exclude_user_id";
        }
    
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindValue(':student_id', $student_id, PDO::PARAM_STR);
        if ($exclude_user_id) {
            $stmt->bindValue(':exclude_user_id', $exclude_user_id, PDO::PARAM_INT);
        }
        $stmt->execute();
    
        return $stmt->fetchColumn() > 0;
    }
    

    public function studentIdExistsForOther($student_id, $user_id)
{
    $query = "SELECT COUNT(*) FROM student_accounts WHERE student_id = :student_id AND user_id != :user_id";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
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

public function getAllCourses() {
    $query = "SELECT course_id, course_code FROM courses";
    return $this->database->fetchAll($query);
}

public function getAllAcademicTerms()
{
    $query = "SELECT * FROM current_academic_term ORDER BY term_id ASC";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function addAcademicTerm($academic_year, $semester, $start_date, $end_date)
{
    $query = "INSERT INTO current_academic_term (academic_year, semester, start_date, end_date, active) 
              VALUES (:academic_year, :semester, :start_date, :end_date, 0)";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->bindParam(':academic_year', $academic_year, PDO::PARAM_STR);
    $stmt->bindParam(':semester', $semester, PDO::PARAM_STR);
    $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
    $stmt->execute();
    return $this->database->connect()->lastInsertId();
}

public function updateAcademicTerm($term_id, $academic_year, $semester, $start_date, $end_date)
{
    $query = "UPDATE current_academic_term 
              SET academic_year = :academic_year, semester = :semester, start_date = :start_date, end_date = :end_date
              WHERE term_id = :term_id";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->bindParam(':academic_year', $academic_year, PDO::PARAM_STR);
    $stmt->bindParam(':semester', $semester, PDO::PARAM_STR);
    $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
    $stmt->bindParam(':term_id', $term_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

public function toggleActiveTerm($term_id)
{
    // Deactivate all terms
    $query = "UPDATE current_academic_term SET active = 0";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->execute();

    // Activate the selected term
    $query = "UPDATE current_academic_term SET active = 1 WHERE term_id = :term_id";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->bindParam(':term_id', $term_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

public function getAllGwaSchedules()
{
    $query = "SELECT g.*, cat.academic_year, cat.semester 
              FROM gwa_submission_schedule g
              JOIN current_academic_term cat ON g.term_id = cat.term_id
              ORDER BY g.submission_id ASC";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function addGwaSchedule($term_id, $gwa_submission_start, $gwa_submission_end)
{
    $query = "INSERT INTO gwa_submission_schedule (term_id, gwa_submission_start, gwa_submission_end, active) 
              VALUES (:term_id, :gwa_submission_start, :gwa_submission_end, 0)";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->bindParam(':term_id', $term_id, PDO::PARAM_INT);
    $stmt->bindParam(':gwa_submission_start', $gwa_submission_start, PDO::PARAM_STR);
    $stmt->bindParam(':gwa_submission_end', $gwa_submission_end, PDO::PARAM_STR);
    $stmt->execute();
    return $this->database->connect()->lastInsertId();
}

public function toggleActiveGwaSchedule($submission_id)
{
    // Deactivate all schedules
    $query = "UPDATE gwa_submission_schedule SET active = 0";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->execute();

    // Activate the selected schedule
    $query = "UPDATE gwa_submission_schedule SET active = 1 WHERE submission_id = :submission_id";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->bindParam(':submission_id', $submission_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

public function getCurrentAcademicTerm() {
    try {
        $query = "SELECT academic_year, semester 
                  FROM current_academic_term 
                  WHERE active = 1 
                  LIMIT 1";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Failed to fetch current academic term: " . $e->getMessage());
    }
}

public function getCurriculumCodes() {
    $query = "SELECT curriculum_code FROM curriculum";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

}
?>