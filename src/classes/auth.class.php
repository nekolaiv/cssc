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

    function __destruct(){
        ob_end_flush();
    }

	private function _isAdmin($email){
		$sql = "SELECT COUNT(*) FROM Admin WHERE email = :email";
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

	private function _isStudent($email){
		$sql = "SELECT COUNT(*) FROM Students WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$query->execute();
		$count = $query->fetchColumn();
		return $count > 0;
	}

	private function _detectRole($email){
		if($this->_isStudent($email)){
			return 'student';
		} else if($this->_isStaff($email)){
			return 'staff';
		} else if($this->_isAdmin($email)){
			return 'admin';
		}
	}

	public function login($email, $password) {
		if($this->_detectRole($email) == 'student'){
			$sql = "SELECT user_id, password, role FROM Students WHERE email = :email";
		} else if($this->_detectRole() == 'staff'){
			$sql = "SELECT user_id, password, role FROM Staffs WHERE email = :email";
		} else if($this->_detectRole() == 'admin'){
			$sql = "SELECT user_id, password, role FROM Admin WHERE email = :email";
		}
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])){
			$_SESSION["user_id"] = $student["user_id"];
			$_SESSION["is_logged-in"] = true;
			$_SESSION["email"] = $student["email"];
			$_SESSION["user_type"] = "student";
            return true;
        }
        return false;
    }

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

	function register(string $email, string $password){
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
		}
		return false;
	}

	function resetPassword($email, $new_password){
		$sql = "UPDATE Students set password = :password WHERE email = :email";
		$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
		if(!$this->_retrievePassword($email, $new_password)){
			$query = $this->database->connect()->prepare($sql);
			$query->bindParam(':email', $email);
			$query->bindParam(':password', $new_hashed_password);
			return $query->execute();
		} else {
			return false;
		}
		
	}

	function emailExists($email){
		$sql = "SELECT COUNT(*) FROM Students WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$query->execute();
		$count = $query->fetchColumn();
		return $count > 0;
	}
}

?>


