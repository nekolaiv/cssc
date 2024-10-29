<?php
namespace Src\Classes;

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("database.class.php");
use Src\Classes\Database;
use PDO;

class Auth{
    protected $database;

    public function __construct(){
        $this->database = new Database();
    }

	public function login($email, $password) {
		if($this->_detectRole($email) == 'student'){
			$sql = "SELECT user_id, password, role FROM Students WHERE email = :email";
			$user_type = 'student';
		} else if($this->_detectRole($email) == 'staff'){
			$sql = "SELECT user_id, password, role FROM Staffs WHERE email = :email";
			$user_type = 'staff';
		} else if($this->_detectRole($email) == 'admin'){
			$sql = "SELECT user_id, password, role FROM Admin WHERE email = :email";
			$user_type = 'admin';
		} else {
			return ['email does not exist', ' '];
		}
		
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
		$user = NULL;
		if($query->execute()){
			$user = $query->fetch(PDO::FETCH_ASSOC);
		}

		if (!password_verify($password, $user['password'])){
			return [' ', 'incorrect password'];	
        } else {
			$_SESSION["user-id"] = $user["user_id"];
			$_SESSION["email"] = $user["email"];
			$_SESSION["user-type"] = $user_type;
			$_SESSION["is-logged-in"] = true;
            return true;
		}
        
    }

	

	public function register(string $email, string $password){
		if($this->emailExists($email, 'student')){
			return 'email exists';
		} else {
			$sql = "INSERT INTO Students(email, password) VALUES(:email, :password)";
			$query = $this->database->connect()->prepare($sql);
			$query->bindParam(':email', $email);
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$query->bindParam(':password', $hashed_password);
			if($query->execute()){
				return true;
			} else {
				return false;
			}
		}
	}

	

	public function resetPassword($email, $new_password){
		$role = $this->_detectRole($email);
		if($this->emailExists($email, $role) === false){
			return ['email does not exists', ' '];
		} else {
			if($role === 'student'){ $sql = "UPDATE Students set password = :password WHERE email = :email"; }
			else if($role === 'staff'){ $sql = "UPDATE Staffs set password = :password WHERE email = :email"; }
			else if($role === 'admin'){ $sql = "UPDATE Admin set password = :password WHERE email = :email"; }
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

	private function emailExists($email, $role){
		if($role === 'student'){ $sql = "SELECT COUNT(*) FROM Students WHERE email = :email"; } 
		else if ($role === 'staff'){ $sql = "SELECT COUNT(*) FROM Staffs WHERE email = :email"; } 
		else if($role === 'admin'){ $sql = "SELECT COUNT(*) FROM Admin WHERE email = :email"; }
		else { return false; }

		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$query->execute();
		$count = $query->fetchColumn();
		return $count > 0;
	}

	private function _retrievePassword($email, $password){
		$sql = "SELECT password FROM Students WHERE email = :email";
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
		$sql = "SELECT COUNT(*) FROM Students WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$query->execute();
		$count = $query->fetchColumn();
		return $count > 0;
	}

	private function _isStaff($email){
		$sql = "SELECT COUNT(*) FROM Staffs WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$query->execute();
		$count = $query->fetchColumn();
		return $count > 0;
	}

	private function _isAdmin($email){
		$sql = "SELECT COUNT(*) FROM Admin WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$query->execute();
		$count = $query->fetchColumn();
		return $count > 0;
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


