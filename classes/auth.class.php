<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("database.class.php");
require_once('../tools/session.function.php');

class Auth{
    protected $database;

    public function __construct(){
        $this->database = new Database();
    }

	// LOGIN FUNCTIONS
	public function login($email, $password) {
		if($this->_detectRole($email) === 'student'){
			$sql = "SELECT * FROM registered_students WHERE email = :email";
		} else if($this->_detectRole($email) === 'staff'){
			$sql = "SELECT * FROM staff_accounts WHERE email = :email";
		} else if($this->_detectRole($email) === 'admin'){
			$sql = "SELECT * FROM admin_accounts WHERE email = :email";
		} else {
			return ['email does not exist', ' '];
		}

		$academic_term = $this->_getCurrentAcademicTerm();
		
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
		$user = NULL;
		if($query->execute()){
			$user = $query->fetch(PDO::FETCH_ASSOC);
		}

		if (!password_verify($password, $user['password'])){
			return [' ', 'incorrect password'];	
        } else {
			if($user['role'] === 'student'){
				$_SESSION['profile'] = [
					'fullname' => $user['last_name'] . ', ' . $user['first_name'] . ' ' . $user['middle_name'],
					'student-id' => $user['student_id'],
					'email' => $user['email'],
					'course' => $user['course'],
					'year-level' => $user['year_level'],
					'adviser' => $user['adviser_name'],
					'school-year' => $academic_term['school_year'],
					'semester' => $academic_term['semester'],
					'status' => $user['status']
				];
				$_SESSION["user-id"] = $user["user_id"];
			} else if($user['role'] === 'staff'){
				$_SESSION['profile'] = [
					'fullname' => $user['last_name'] . ', ' . $user['first_name'] . ' ' . $user['middle_name'],
					'email' => $user['email'],
				];
				$_SESSION["user-id"] = $user["staff_id"];
			} else if($user['role'] === 'admin'){
				$_SESSION['profile'] = [
					'fullname' => $user['last_name'] . ', ' . $user['first_name'] . ' ' . $user['middle_name'],
					'email' => $user['email'],
				];
				$_SESSION["user-id"] = $user["admin_id"];
			}
			$_SESSION["user-type"] = $user['role'];
			$_SESSION["is-logged-in"] = true;
            return true;
		}
    }

	private function _getCurrentAcademicTerm(){
		$sql = "SELECT * FROM current_academic_term";
		$query = $this->database->connect()->prepare($sql);
		$data=NULL;
		if($query->execute()){
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data;
		} else {
			return false;
		}
	}

	private function _detectRole($email){
		if($this->_isStudent($email)){
			return 'student';
		} else if($this->_isStaff($email)){
			return 'staff';
		} else if($this->_isAdmin($email)){
			return 'admin';
		} else {
			return false;
		}
	}

	private function _isStudent($email){
		$sql = "SELECT COUNT(*) FROM registered_students WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$query->execute();
		$count = $query->fetchColumn();
		return $count > 0;
	}

	private function _isStaff($email){
		$sql = "SELECT COUNT(*) FROM staff_accounts WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$query->execute();
		$count = $query->fetchColumn();
		return $count > 0;
	}

	private function _isAdmin($email){
		$sql = "SELECT COUNT(*) FROM admin_accounts WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$query->execute();
		$count = $query->fetchColumn();
		return $count > 0;
	}

	// REGISTRATION FUNCTIONS
	public function register(string $email, string $password){
		if($this->emailExists($email, 'student')){
			return 'email exists';
		} else {
			$student = $this->_isCCSEmail($email);
			if($student){
				$sql = "INSERT INTO registered_students(student_id, email, password, first_name, last_name, middle_name, course, year_level, section, adviser_name) 
				VALUES(:student_id, :email, :password, :first_name, :last_name, :middle_name, :course, :year_level, :section, :adviser_name)";
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				$adviser = $this->_getAdviser($student['year_level']);
				$adviser_name = $adviser['adviser_name'] ?? ' ';
				$role = 'student';
				$query = $this->database->connect()->prepare($sql);
				$query->bindParam(':student_id', $student['student_id']);
				$query->bindParam(':email', $email);
				$query->bindParam(':password', $hashed_password);
				$query->bindParam(':first_name', $student['first_name']);
				$query->bindParam(':last_name', $student['last_name']);
				$query->bindParam(':middle_name', $student['middle_name']);
				$query->bindParam(':course', $student['course']);
				$query->bindParam(':year_level', $student['year_level']);
				$query->bindParam(':section', $student['section']);
				$query->bindParam(':adviser_name', $adviser_name);
				if($query->execute()){
					return true;
				} else {
					return 'execution failed';
				}
			} else {
				return 'email is not from CCS';
			}
		}
	}

	private function emailExists($email, $role){
		if($role === 'student'){ $sql = "SELECT COUNT(*) FROM registered_students WHERE email = :email"; } 
		else if ($role === 'staff'){ $sql = "SELECT COUNT(*) FROM staff_accounts WHERE email = :email"; } 
		else if($role === 'admin'){ $sql = "SELECT COUNT(*) FROM admin_accounts WHERE email = :email"; }
		else { return false; }

		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$query->execute();
		$count = $query->fetchColumn();
		return $count > 0;
	}

	private function _isCCSEmail($email){
		$sql = "SELECT * FROM unregistered_students WHERE email = :email LIMIT 1;";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$student=NULL;
		if($query->execute()){
			$student = $query->fetch(PDO::FETCH_ASSOC);
			return $student;
		} else {
			return false;
		}
	}

	// private function _isFromCS($email){
	// 	$sql = "SELECT COUNT(*) FROM List_of_CS_Students WHERE email = :email";
	// 	$query = $this->database->connect()->prepare($sql);
	// 	$query->bindParam(':email', $email);
	// 	$query->execute();
	// 	$count = $query->fetchColumn();
	// 	return $count > 0;
	// }

	// private function _isFromIT($email){
	// 	$sql = "SELECT COUNT(*) FROM List_of_IT_Students WHERE email = :email";
	// 	$query = $this->database->connect()->prepare($sql);
	// 	$query->bindParam(':email', $email);
	// 	$query->execute();
	// 	$count = $query->fetchColumn();
	// 	return $count > 0;
	// }
	
	// private function _isFromACT($email){
	// 	$sql = "SELECT COUNT(*) FROM List_of_ACT_Students WHERE email = :email";
	// 	$query = $this->database->connect()->prepare($sql);
	// 	$query->bindParam(':email', $email);
	// 	$query->execute();
	// 	$count = $query->fetchColumn();
	// 	return $count > 0;
	// }

	private function _getAdviser($year_level){
		$sql = "SELECT id, CONCAT(last_name, ', ', first_name, ' ', middle_name) as adviser_name FROM advisers WHERE year_level = :year_level LIMIT 1";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':year_level', $year_level);
		$adviser=NULL;
		if($query->execute()){
			$adviser = $query->fetch(PDO::FETCH_ASSOC);
			return $adviser;
		}
	}

	// RESET PASSWORD FUNCTIONS
	public function resetPassword($email, $new_password){
		$role = $this->_detectRole($email);
		if($this->emailExists($email, $role) === false){
			return ['email does not exists', ' '];
		} else {
			if($role === 'student'){ $sql = "UPDATE registered_students set password = :password WHERE email = :email"; }
			else if($role === 'staff'){ $sql = "UPDATE staff_accounts set password = :password WHERE email = :email"; }
			else if($role === 'admin'){ $sql = "UPDATE admin_accounts set password = :password WHERE email = :email"; }
		}

		$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
		if(!$this->_retrievePassword($email, $new_password)){
			$query = $this->database->connect()->prepare($sql);
			$query->bindParam(':email', $email);
			$query->bindParam(':password', $new_hashed_password);
			return $query->execute();
		} else {
			return [' ', 'similar to old password'];
		}
	}

	private function _retrievePassword($email, $password){
		$sql = "SELECT password FROM registered_students WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$retrieved_password=null;
		if($query->execute()){
			$retrieved_password = $query->fetch();
		}
		if(password_verify($password, $retrieved_password["password"])){ 
			return true;
		} else { 
			return false; 
		}
	}


	// =================== DUMPS BUT MIGHT BE USEFUL =================== 
	// public function register($username, $password) {
    //     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
    //     $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'user')");
    //     $stmt->bindParam(':username', $username);
    //     $stmt->bindParam(':password', $hashedPassword);
        
    //     return $stmt->execute(); // Returns true on success
    // }


    // function login($email, $password) {
	// 	$sql = "SELECT * FROM Students WHERE email = :email";
	// 	$query = $this->database->connect()->prepare($sql);
	// 	$query->bindParam(':email', $email);
	// 	$student=null;
	// 	if($query->execute()){
	// 		$student = $query->fetch();
	// 	}

	// 	if (!$student) {
	// 		header("location:" . $_SERVER["HTTP_REFERER"]);
	// 		return;
	// 	}
	// 	if (password_verify($password, $student["password"])){
	// 		$_SESSION["user_id"] = $student["user_id"];
	// 		$_SESSION["is_loggedIn"] = TRUE;
	// 		$_SESSION["email"] = $student["email"];
	// 		$_SESSION["user_type"] = "student";
	// 		header("location: " . $_SERVER["HTTP_REFERER"]);
	// 	} else {
	// 		return False;
	// 	}
  	// }
}

?>


