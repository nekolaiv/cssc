<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "database.class.php";

class Auth{
    protected $database;

    public function __construct(){
        $this->database = new Database();
    }

    function __destruct(){
        ob_end_flush();
    }

    function studentLogin() {
		extract($_POST);
		// $sql = "SELECT * FROM students WHERE email = '$email'";
		// $user = $this->database->query($sql)->fetch_assoc();
		$sql = "SELECT * FROM Students WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$student=null;
		if($query->execute()){
			$student = $query->fetch();
		}

		if (!$student) {
			header("location:" . $_SERVER["HTTP_REFERER"]);
			return;
		}

		// if (password_verify($_POST["password"], $student["password"])) 
		if ($_POST["password"] == $student["password"]){
			$_SESSION["user_id"] = $student["user_id"];
			$_SESSION["is_loggedIn"] = TRUE;
			$_SESSION["email"] = $student["email"];
			$_SESSION["user_type"] = "student";
			header("location: " . $_SERVER["HTTP_REFERER"]);
		}  
  	}

	function studentResetPassword(){
		extract($_POST);

		$sql = "UPDATE Student set password = :password WHERE email = :email";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $_POST['email']);
		return $query->execute();
	}


}

?>


