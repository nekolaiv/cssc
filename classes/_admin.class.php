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
     * Check if a student ID or email already exists
     */
    public function checkStudentExists($student_id, $email) {
        try {
            $sql = "SELECT COUNT(*) as count FROM students_info WHERE student_id = :student_id OR email = :email";
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            throw new Exception("Error checking student existence: " . $e->getMessage());
        }
    }

    /**
     * Create a new student
     */
    public function createStudent($student_id, $first_name, $middle_name, $last_name, $email, $password, $course_id, $year_level_id, $section_id) {
        if ($this->checkStudentExists($student_id, $email)) {
            return ["success" => false, "message" => "Student ID or Email already exists."];
        }
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO students_info (student_id, first_name, middle_name, last_name, email, password) 
                    VALUES (:student_id, :first_name, :middle_name, :last_name, :email, :password)";
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':middle_name', $middle_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->execute();

            $sql_account = "INSERT INTO student_accounts (student_id, course_id, year_level_id, section_id) 
                            VALUES (:student_id, :course_id, :year_level_id, :section_id)";
            $stmt_account = $this->database->connect()->prepare($sql_account);
            $stmt_account->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt_account->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt_account->bindParam(':year_level_id', $year_level_id, PDO::PARAM_INT);
            $stmt_account->bindParam(':section_id', $section_id, PDO::PARAM_INT);
            $stmt_account->execute();

            return ["success" => true, "message" => "Student created successfully."];
        } catch (PDOException $e) {
            throw new Exception("Error creating student: " . $e->getMessage());
        }
    }

    /**
     * Update a student's information
     */
    public function updateStudent($student_id, $first_name, $middle_name, $last_name, $email, $course_id, $year_level_id, $section_id, $password = null) {
        if ($this->checkStudentExists($student_id, $email)) {
            return ["success" => false, "message" => "Student ID or Email already exists for another record."];
        }
        try {
            $hashed_password = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

            $sql = "UPDATE students_info 
                    SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name, email = :email
                    " . ($hashed_password ? ", password = :password" : "") . " 
                    WHERE student_id = :student_id";
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':middle_name', $middle_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            if ($hashed_password) {
                $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            }
            $stmt->execute();

            $sql_account = "UPDATE student_accounts 
                            SET course_id = :course_id, year_level_id = :year_level_id, section_id = :section_id 
                            WHERE student_id = :student_id";
            $stmt_account = $this->database->connect()->prepare($sql_account);
            $stmt_account->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt_account->bindParam(':year_level_id', $year_level_id, PDO::PARAM_INT);
            $stmt_account->bindParam(':section_id', $section_id, PDO::PARAM_INT);
            $stmt_account->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt_account->execute();

            return ["success" => true, "message" => "Student updated successfully."];
        } catch (PDOException $e) {
            throw new Exception("Error updating student: " . $e->getMessage());
        }
    }

    /**
     * Delete a student
     */
    public function deleteStudent($student_id) {
        try {
            $sql = "DELETE FROM students_info WHERE student_id = :student_id";
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();

            $sql_account = "DELETE FROM student_accounts WHERE student_id = :student_id";
            $stmt_account = $this->database->connect()->prepare($sql_account);
            $stmt_account->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt_account->execute();

            return ["success" => true, "message" => "Student deleted successfully."];
        } catch (PDOException $e) {
            throw new Exception("Error deleting student: " . $e->getMessage());
        }
    }

    /**
     * Retrieve students with filters
     */
    public function getStudents($filter = [], $limit = 10, $offset = 0) {
        try {
            $sql = "
                SELECT 
                    si.student_id, 
                    si.first_name, 
                    si.middle_name, 
                    si.last_name, 
                    si.email, 
                    c.course_code, 
                    yl.year_level_name, 
                    s.section_code
                FROM 
                    students_info si
                INNER JOIN 
                    student_accounts sa ON si.student_id = sa.student_id
                INNER JOIN 
                    courses c ON sa.course_id = c.course_id
                INNER JOIN 
                    year_levels yl ON sa.year_level_id = yl.year_level_id
                INNER JOIN 
                    sections s ON sa.section_id = s.section_id
            ";
    
            $conditions = [];
            if (isset($filter['name'])) {
                $conditions[] = "(si.first_name LIKE :name OR si.last_name LIKE :name)";
            }
            if ($conditions) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
            $sql .= " LIMIT :limit OFFSET :offset";
    
            $stmt = $this->database->connect()->prepare($sql);
            if (isset($filter['name'])) {
                $name = "%" . $filter['name'] . "%";
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            }
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching students: " . $e->getMessage());
        }
    }

    public function countStudents($filter = []) {
        try {
            $sql = "
                SELECT COUNT(*) AS total
                FROM 
                    students_info si
                INNER JOIN 
                    student_accounts sa ON si.student_id = sa.student_id
            ";
    
            $conditions = [];
            if (isset($filter['name'])) {
                $conditions[] = "(si.first_name LIKE :name OR si.last_name LIKE :name)";
            }
            if ($conditions) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
    
            $stmt = $this->database->connect()->prepare($sql);
            if (isset($filter['name'])) {
                $name = "%" . $filter['name'] . "%";
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            throw new Exception("Error counting students: " . $e->getMessage());
        }
    }
    
    

    /**
 * Get dropdown options for courses, year levels, and sections
 */
public function getDropdownData() {
    try {
        $sqlCourses = "SELECT course_id, course_code FROM courses";
        $sqlYearLevels = "SELECT year_level_id, year_level_name FROM year_levels";
        $sqlSections = "SELECT section_id, section_code FROM sections";

        $courses = $this->database->connect()->query($sqlCourses)->fetchAll(PDO::FETCH_ASSOC);
        $yearLevels = $this->database->connect()->query($sqlYearLevels)->fetchAll(PDO::FETCH_ASSOC);
        $sections = $this->database->connect()->query($sqlSections)->fetchAll(PDO::FETCH_ASSOC);

        return [
            "courses" => $courses,
            "year_levels" => $yearLevels,
            "sections" => $sections
        ];
    } catch (PDOException $e) {
        throw new Exception("Error fetching dropdown data: " . $e->getMessage());
    }
}

    
}
?>
