<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("database.class.php");

class Student
{

    protected $database;

    public function __construct(){
        $this->database = new Database();
    }


    // CALCULATE MODELS
    public function calculateGWA($subject_codes, $grades, $units){
        $totalPoints = 0;
        $totalUnits = 0;

        for ($i = 0; $i < count($subject_codes); $i++) {
            $totalPoints += $grades[$i] * $units[$i];
            $totalUnits += $units[$i];
        }

        $gwa = $totalUnits > 0 ? $totalPoints / $totalUnits : 0;

        return $gwa;
    }

    private function _studentUnverifiedEntryExists($email){
        $sql = "SELECT COUNT(*) FROM Students_Unverified_Entries WHERE email = :email LIMIT 1;";
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if ($query->execute()) {
            $row_count = $query->fetchColumn();
            return $row_count > 0;
        } else {
            return false;
        }
    }

    private function _studentVerifiedEntryExists($email){
        $sql = "SELECT COUNT(*) FROM Students_Verified_Entries WHERE email = :email LIMIT 1;";
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if ($query->execute()) {
            $row_count = $query->fetchColumn();
            return $row_count > 0;
        } 
        return false;
    }

    public function getStudentEntry($email){
        if($this->_studentUnverifiedEntryExists($email)){
            $sql = "SELECT * FROM Students_Unverified_Entries WHERE email = :email LIMIT 1;";
        } else if ($this->_studentVerifiedEntryExists($email)){
            $sql = "SELECT * FROM Students_Verified_Entries WHERE email = :email LIMIT 1;";
        }
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        $data = NULL;
        if ($query->execute()) {
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        } else {
            return false;
        }
    }

    private function _isEntryPending($email){
        $sql = 'SELECT COUNT(*) FROM Students_Unverified_Entries WHERE email = :email LIMIT 1;';
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if($query->execute()){
            $row_count = $query->fetchColumn(PDO::FETCH_ASSOC);
            return $row_count > 0;
        } else {
            return false;
        }
    }

    private function _isEntryVerified($email){
        $sql = 'SELECT COUNT(*) FROM Students_Verified_Entries WHERE email = :email LIMIT 1;';
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if($query->execute()){
            $row_count = $query->fetchColumn(PDO::FETCH_ASSOC);
            return $row_count > 0;
        } else {
            return false;
        }
    }

    private function _updateStudentStatus($email){
        if($this->_isEntryPending($email)){
            $sql = 'UPDATE Registered_Students SET status = "Pending" WHERE email = :email;';
        } else if($this->_isEntryVerified($email)){
            $sql = 'UPDATE Registered_Students SET status = "Verified" WHERE email = :email;';
        } else {
            $sql = 'UPDATE Registered_Students SET status = "Not Submitted" WHERE email = :email;';
        }
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if($query->execute()){
            return true;
        } else {
            return false;
        }
    }

    // LEADERBOARD MODELS
    public function getCSTopNotcher(){
        $sql = 'SELECT fullname, gwa, created_at FROM Students_Verified_Entries WHERE course = "Computer Science" ORDER BY gwa, created_at ASC LIMIT 1;';
        $query = $this->database->connect()->prepare($sql);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetch(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getITTopNotcher(){
        $sql = 'SELECT fullname, gwa, created_at FROM Students_Verified_Entries WHERE course = "Information Technology" ORDER BY gwa, created_at ASC LIMIT 1;';
        $query = $this->database->connect()->prepare($sql);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetch(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getACTTopNotcher(){
        $sql = 'SELECT fullname, gwa, created_at FROM Students_Verified_Entries WHERE course = "Associate in Computer Technology" ORDER BY gwa, created_at ASC LIMIT 1;';
        $query = $this->database->connect()->prepare($sql);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetch(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getCSLeaderboardData(){
        $sql = 'SELECT * FROM Students_Verified_Entries WHERE course = "Computer Science" ORDER BY gwa, fullname ASC';
        $query = $this->database->connect()->prepare($sql);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getITLeaderboardData(){
        $sql = 'SELECT * FROM Students_Verified_Entries WHERE course = "Information Technology" ORDER BY gwa, fullname ASC';
        $query = $this->database->connect()->prepare($sql);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getACTLeaderboardData(){
        $sql = 'SELECT * FROM Students_Verified_Entries WHERE course = "Associate in Computer Technology" ORDER BY gwa, fullname ASC';
        $query = $this->database->connect()->prepare($sql);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
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

    // RESULT MODELS
    public function saveEntryToDatabase($email, $gwa){
        if ($this->_entryExists($email)) {
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
        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    private function _entryExists($email){
        $sql = "SELECT COUNT(*) FROM Students_Unverified_Entries WHERE email = :email";
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if ($query->execute()) {
            $count = $query->fetchColumn();
            return $count > 0;
        } else {
            return false;
        }
    }

    private function _getStudentData($email){
        $this->_updateStudentStatus($email);
        $sql = "SELECT * FROM Registered_Students WHERE email = :email LIMIT 1;";
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        $student = NULL;
        if ($query->execute()) {
            $student = $query->fetch(PDO::FETCH_ASSOC);
            return $student;
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