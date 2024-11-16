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


class Student{

    protected $database;

    public function __construct(){
        $this->database = new Database();
    }


    public function calculateGWA() {
        $totalPoints = 0;
        $totalUnits = 0;

        for ($i = 0; $i < count($_SESSION['course-fields']['subject-code']); $i++) {
            $totalPoints += $_SESSION['course-fields']['grade'][$i] * $_SESSION['course-fields']['unit'][$i];
            $totalUnits += $_SESSION['course-fields']['unit'][$i];
        }

        $gwa = $totalUnits > 0 ? $totalPoints / $totalUnits : 0;
        $result = $gwa <= 2.0 ? true : false;

        return [$result, $gwa];
    }

    // private function _getEntryForDatabase() {
    //     $query->bindParam(':student_id', $student['student_id']);
    //     $query->bindParam(':email', $email);
    //     $query->bindParam(':password', $hashed_password);
    //     $query->bindParam(':first_name', $student['first_name']);
    //     $query->bindParam(':last_name', $student['last_name']);
    //     $query->bindParam(':middle_name', $student['middle_name']);
    //     $query->bindParam(':course', $student['course']);
    //     $query->bindParam(':year_level', $student['year_level']);
    //     $query->bindParam(':section', $student['section']);
    //     $query->bindParam(':adviser_name', $adviser_name);
    // }

    private function _getStudentData($email) {
        $sql = "SELECT * FROM Registered_Students WHERE email = :email LIMIT 1;";
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

    private function _entryExists($email) {
        $sql = "SELECT COUNT(*) FROM Students_Unverified_Entries WHERE email = :email";
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if($query->execute()){
            $count = $query->fetchColumn();
            return $count > 0;
        } else {
            return false;
        }
    }

    public function saveEntryToDatabase($email, $gwa) {
        if($this->_entryExists($email)) {
            $sql = "UPDATE Students_Unverified_Entries SET gwa = :gwa WHERE email = :email";
            $query = $this->database->connect()->prepare($sql);
            $query->bindParam(':gwa', $gwa);
            $query->bindParam(':email', $email);
        } else {
            $sql = "INSERT INTO Students_Unverified_Entries(student_id, email, fullname, course, year_level, section, adviser_name, gwa)
            VALUES(:student_id, :email, :fullname, :course, :year_level, :section, :adviser_name, :gwa)";
            $student = $this->_getStudentData($email);
            $student_fullname = $student['last_name'] . ', ' . $student['first_name'] . ' ' . $student['middle_name'];
            $query = $this->database->connect()->prepare($sql);
            $query->bindParam(':student_id', $student['student_id']);
            $query->bindParam(':email', $email);
            $query->bindParam(':fullname', $student_fullname);
            $query->bindParam(':course', $student['course']);
            $query->bindParam(':year_level', $student['year_level']);
            $query->bindParam(':section', $student['section']);
            $query->bindParam(':adviser_name', $student['adviser_name']);
            $query->bindParam(':gwa', $gwa);
        }
        if($query->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function getVerificationStatus(){
        $sql = "SELECT ";
    }

    // public function handleRequest() {
    //     session_start(); // Start the session

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         if (isset($_GET['action']) && $_GET['action'] === 'saveCourse') {
    //             $this->saveCourseToSession();
    //         } else {
    //             $this->calculateGPA();
    //         }
    //     } else {
    //         $this->showForm();
    //     }
    // }

    // private function showForm() {
    //     // Fetch courses from the session
    //     $courses = $_SESSION['courses'] ?? [];
    //     require_once '../resources/student/course_form.php';
    // }

    // private function saveCourseToSession() {
    //     // Validate and save course data to session
    //     $subjectCode = $_POST['subjectCode'];
    //     $units = $_POST['units'];
    //     $grades = $_POST['grades'];

    //     // Initialize session array if not set
    //     if (!isset($_SESSION['courses'])) {
    //         $_SESSION['courses'] = [];
    //     }

    //     $_SESSION['courses'][] = [
    //         'subjectCode' => $subjectCode,
    //         'units' => $units,
    //         'grades' => $grades
    //     ];
    // }
}


?>


