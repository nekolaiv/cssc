<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/database.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/clean.function.php');

class Auth {
    protected $database;

    public function __construct() {
        $this->database = new Database();
    }

    // LOGIN FUNCTION
    public function login($email, $password) {
        try {
            // Query to get user credentials and role
            $sql = "
                SELECT a.id AS account_id, u.id AS user_id, u.identifier AS identifier, a.password, a.status, r.name AS role, 
                       u.firstname, u.middlename, u.lastname, u.email
                FROM account a
                JOIN user u ON a.user_id = u.id
                JOIN role r ON a.role_id = r.id
                WHERE u.email = :email
            ";
    
            $query = $this->database->connect()->prepare($sql);
            $query->bindParam(':email', $email);
            $query->execute();
    
            if ($query->rowCount() == 0) {
                return ['Email does not exist', ' '];
            }
    
            $user = $query->fetch(PDO::FETCH_ASSOC);
            $academic_term = $this->_getCurrentAcademicTerm();
            $adviser = $this->_getStudentAdviser($user['account_id']);
            $course = $this->_getStudentCourse($user['identifier']);
            $year_level = $this->_getStudentYearLevel($user['identifier']);
            $status = $this->_getStudentStatus($user['account_id']);
            $status === false ? $entry_status = NULL : $entry_status = $status;
    
            // Check if account is inactive
            if ($user['status'] !== 'active') {
                return ['Account is inactive', ' '];
            }
    
            // Verify password
            if ($password == $user['password']){
                echo '<script> alert("First Login Detected!\nKindly create a strong password");</script>';
                return 'first login';	
            } else if (!password_verify($password, $user['password'])){
                return [' ', 'incorrect password'];	
            }
    
            // Set session variables
            regenerateSession();
            if($user['role'] !== 'user'){
                $_SESSION['user-id'] = $user['account_id'];
                $_SESSION['user-table-id'] = $user['user_id']; // Adding the user_id from the user table
                $_SESSION['user-name'] = $user['lastname'] . ', ' . $user['firstname'] . ' ' . $user['middlename'];
                $_SESSION['user-email'] = $user['email'];
                $_SESSION['user-role'] = $user['role'];
                $_SESSION['is-logged-in'] = true;
            } else {
                $_SESSION['profile'] = [
                    'user-id' => $user['account_id'],
                    'user-table-id' => $user['user_id'], // Adding the user_id from the user table
                    'user-role' => $user['role'],
                    'user-name' => $user['lastname'] . ', ' . $user['firstname'] . ' ' . $user['middlename'],
                    'student-id' => $user['identifier'],
                    'user-email' => $user['email'],
                    'course' => $course['course_name'],
                    'adviser' => $adviser['adviser_name'],
                    'school-year' => $academic_term['year'],
                    'semester' => $academic_term['semester'],
                    'year-level' => $year_level['student_year'],
                    'status' => $entry_status['status'],
                  ];
                $_SESSION['is-logged-in'] = true;
            }
            return true;
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return ['Something went wrong', ' '];
        }
    }
    

    private function _getCurrentAcademicTerm(){
		$sql = "SELECT * FROM dean_lister_application_periods WHERE status = 'open'";
		$query = $this->database->connect()->prepare($sql);
		$data=NULL;
		if($query->execute()){
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data;
		} else {
			return false;
		}
	}

    private function _getStudentStatus($user_id){
        $sql = "SELECT status FROM student_applications WHERE user_id = :user_id";
		$query = $this->database->connect()->prepare($sql);
        $query->bindParam(':user_id', $user_id);
		$data=NULL;
		if($query->execute()){
            if ($query->rowCount() > 0){
                $data = $query->fetch(PDO::FETCH_ASSOC);
			    return $data;
            } else {
                return false;
            }
		} else {
			return false;
		}
    }

    private function _getStudentYearLevel($id){
        $sql = "SELECT (CAST(RIGHT(dlap.year, 4) AS SIGNED) - CAST(LEFT(u.identifier, 4) AS SIGNED)) AS student_year
        FROM user AS u, dean_lister_application_periods AS dlap
        WHERE u.identifier = :id AND dlap.status = 'open';";
        $query = $this->database->connect()->prepare($sql);
		$query->bindParam(':id', $id);
		$data=NULL;
		if($query->execute()){
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data;
		} else {
			return false;
		}
    }

	private function _getStudentAdviser($id){
		$sql = "SELECT CONCAT(a.firstname, ', ', a.lastname, ' ', a.middlename) as adviser_name
		FROM user as u
        LEFT JOIN adviser as a ON u.id = a.user_id
		WHERE u.id = :id;";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':id', $id);
		$data=NULL;
		if($query->execute()){
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data;
		} else {
			return false;
		}
	}


    private function _getStudentCourse($id){
		$sql = 'SELECT u.department_id AS department_id, c.course_name AS course_name
        FROM user AS u
        LEFT JOIN course as c ON u.department_id = c.id
        WHERE u.identifier = :id';
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':id', $id);
		$data=NULL;
		if($query->execute()){
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data;
            
		} else {
			return false;
		}
	}

    // RESET PASSWORD FUNCTION
    public function resetPassword($email, $new_password) {
        try {
            // Check if email exists
            $user = $this->_getUserByEmail($email);
            if (!$user) {
                return ['Email does not exist', ''];
            }

            // Check if new password is the same as the old password
            if (password_verify($new_password, $user['password'])) {
                return [' ', 'New password cannot be the same as the old password'];
            }

            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE account SET password = :password WHERE id = :account_id";
            $query = $this->database->connect()->prepare($sql);
            $query->bindParam(':password', $hashed_password);
            $query->bindParam(':account_id', $user['account_id']);

            if ($query->execute()) {
                return true;
            } else {
                return ['Failed to reset password', ' '];
            }
        } catch (PDOException $e) {
            error_log("Password Reset Error: " . $e->getMessage());
            return ['Something went wrong', ' '];
        }
    }

    // HELPER FUNCTION: Get user by email
    private function _getUserByEmail($email) {
        $sql = "SELECT a.id AS account_id, a.password
            FROM account a
            JOIN user u ON a.user_id = u.id
            WHERE u.email = :email
        ";

        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        $query->execute();

        if ($query->rowCount() > 0) {
            return $query->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
}
?>
