<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("database.class.php");
require_once('../tools/session.function.php');

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
                SELECT a.id AS account_id, u.identifier AS identifier, a.password, a.status, r.name AS role, 
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
                return ['Email does not exist', ''];
            }

            $user = $query->fetch(PDO::FETCH_ASSOC);
            $academic_term = $this->_getCurrentAcademicTerm();
            $adviser = $this->_getStudentAdviser($user['account_id']);
            $course = $this->_getStudentCourse($user['identifier']);

            // Check if account is inactive
            if ($user['status'] !== 'active') {
                return ['Account is inactive.', ''];
            }

            // Verify password
            if ($password == $user['password']){
                // echo '<script> alert("First Login Detected!\nKindly create a strong password");</script>';
                return 'first login';	
            } else if (!password_verify($password, $user['password'])){
                return [' ', 'incorrect password'];	
            }

            // Set session variables
            regenerateSession();
            if($user['role'] !== 'user'){
                $_SESSION['user-id'] = $user['account_id'];
                $_SESSION['user-name'] = $user['lastname'] . ', ' . $user['firstname'] . ' ' . $user['middlename'];
                $_SESSION['user-email'] = $user['email'];
                $_SESSION['user-role'] = $user['role'];
                $_SESSION['is-logged-in'] = true;
            } else {
                $_SESSION['profile'] = [
                    'user-id' => $user['account_id'],
                    'user-role' => $user['role'],
					'user-name' => $user['lastname'] . ', ' . $user['firstname'] . ' ' . $user['middlename'],
					'student-id' => $user['identifier'],
					'user-email' => $user['email'],
					'course' => $course['department_name'],
					'adviser' => $adviser['adviser_name'],
					'school-year' => $academic_term['year'],
					'semester' => $academic_term['semester'],
				];
				$_SESSION['is-logged-in'] = true;
            }
            return true;
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return ['Something went wrong', $e->getMessage()];
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
		$sql = 'SELECT u.identifier, d.department_name AS department_name
        FROM user AS u
        LEFT JOIN department as d ON u.department_id = d.id
        WHERE u.identifier = :id';
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':id', $id);
		$data=NULL;
		if($query->execute()){
			$data = $query->fetch(PDO::FETCH_ASSOC);
            $_SESSION['GETCOURSE'] = 'true';
			return $data;
            
		} else {
            $_SESSION['GETCOURSE'] = 'false';
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
                return ['', 'New password cannot be the same as the old password'];
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
                return ['Failed to reset password', ''];
            }
        } catch (PDOException $e) {
            error_log("Password Reset Error: " . $e->getMessage());
            return ['Something went wrong', ''];
        }
    }

    // HELPER FUNCTION: Get user by email
    private function _getUserByEmail($email) {
        $sql = "
            SELECT a.id AS account_id, a.password
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
