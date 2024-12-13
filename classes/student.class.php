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
        $sql = "SELECT COUNT(*) FROM students_unverified_entries WHERE email = :email LIMIT 1;";
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
        $sql = "SELECT COUNT(*) FROM students_verified_entries WHERE email = :email LIMIT 1;";
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if ($query->execute()) {
            $row_count = $query->fetchColumn();
            return $row_count > 0;
        } 
        return false;
    }

    private function getStudentEntry($email){
        if($this->_studentUnverifiedEntryExists($email)){
            $sql = "SELECT * FROM students_unverified_entries WHERE email = :email LIMIT 1;";
        } else if ($this->_studentVerifiedEntryExists($email)){
            $sql = "SELECT * FROM students_verified_entries WHERE email = :email LIMIT 1;";
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

    public function getStudentImageProof( $email ){
        $data = $this->getStudentEntry($email);
        return $data['image_proof'];
    }

    // UPDATING STUDENT STATUS
    private function _updateStudentStatus($email){
        if($this->_isEntryPending($email)){
            if(!$this->_isStatusRejected($email)){
                return true;
            }
            $sql = 'UPDATE student_accounts SET status = "Pending" WHERE email = :email;';
        } else if($this->_isEntryVerified($email)){
            $sql = 'UPDATE student_accounts SET status = "Verified" WHERE email = :email;';
        } else {
            $sql = 'UPDATE student_accounts SET status = "Not Submitted" WHERE email = :email;';
        }
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if($query->execute()){
            return true;
        } else {
            return false;
        }
    }

    private function _isEntryPending($email){
        $sql = 'SELECT COUNT(*) FROM students_unverified_entries WHERE email = :email LIMIT 1;';
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if($query->execute()){
            $row_count = $query->fetchColumn();
            return $row_count > 0;
        } else {
            return false;
        }
    }

    private function _isStatusRejected($email){
        $sql = 'SELECT status FROM student_accounts WHERE email = :email LIMIT 1;';
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        } else {
            return false;
        }
    }

    private function _isEntryVerified($email){
        $sql = 'SELECT COUNT(*) FROM students_verified_entries WHERE email = :email LIMIT 1;';
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        if($query->execute()){
            $row_count = $query->fetchColumn();
            return $row_count > 0;
        } else {
            return false;
        }
    }

    // ASSIGNING STUDENT CURRICULAR SUBJECTS
    public function loadStudentsSubjects($email){
        $sql = 'SELECT ss.subject_name, ss.subject_code, ss.units
        FROM student_accounts as sa
        LEFT JOIN curriculum_subjects as cs ON sa.curriculum_code = cs.curriculum_code
        LEFT JOIN student_subjects as ss ON cs.subject_id = ss.subject_id
        WHERE sa.email = :email';
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    // LEADERBOARD MODELS
    public function getCSTopNotcher($year_level = NULL){
        if($year_level === NULL){
            $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "1" ORDER BY gwa, created_at ASC LIMIT 1;';
        } else {
            $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "1" AND year_level = :year_level ORDER BY gwa, created_at ASC LIMIT 1;';
        }
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':year_level', $year_level);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetch(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getITTopNotcher($year_level = NULL){
        if($year_level === NULL){
            $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "2" ORDER BY gwa, created_at ASC LIMIT 1;';
        } else {
            $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "2" AND year_level = :year_level ORDER BY gwa, created_at ASC LIMIT 1;';
        }
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':year_level', $year_level);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetch(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getACTTopNotcher($year_level = NULL){
        if($year_level === NULL){
            $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "3" ORDER BY gwa, created_at ASC LIMIT 1;';
        } else {
            $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "3" AND year_level = :year_level ORDER BY gwa, created_at ASC LIMIT 1;';
        }
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':year_level', $year_level);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetch(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }


    public function getCSLeaderboardData($year_level = NULL){
        if($year_level === NULL){
            $sql = 'SELECT * FROM students_verified_entries WHERE course = "1" ORDER BY gwa, fullname ASC';
        } else {
            $sql = 'SELECT * FROM students_verified_entries WHERE course = "1" AND year_level = :year_level ORDER BY gwa, fullname ASC';
        }
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':year_level', $year_level);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getITLeaderboardData($year_level = NULL){
        if($year_level === NULL){
            $sql = 'SELECT * FROM students_verified_entries WHERE course = "2" ORDER BY gwa, fullname ASC';
        } else {
            $sql = 'SELECT * FROM students_verified_entries WHERE course = "2" AND year_level = :year_level ORDER BY gwa, fullname ASC';
        }
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':year_level', $year_level);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getACTLeaderboardData($year_level = NULL){
        if($year_level === NULL){
            $sql = 'SELECT * FROM students_verified_entries WHERE course = "3" ORDER BY gwa, fullname ASC';
        } else {
            $sql = 'SELECT * FROM students_verified_entries WHERE course = "3" AND year_level = :year_level ORDER BY gwa, fullname ASC';
        }
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':year_level', $year_level);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getStudentLeaderboardData($year_level = NULL, $course = NULL){
        if($year_level === NULL){
            $sql = 'SELECT gwa, fullname FROM students_unverified_entries WHERE course = :course ORDER BY gwa, fullname ASC';
        } else {
            $sql = 'SELECT gwa, fullname FROM students_unverified_entries WHERE course = :course AND year_level = :year_level ORDER BY gwa, fullname ASC';
        }
        $query = $this->database->connect()->prepare($sql);
        if($year_level !== NULL){
            $query->bindParam(':year_level', $year_level);
        }
        $query->bindParam(':course', $course);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function getStudentTopNotcher($year_level = NULL, $course = NULL){
        if($year_level === NULL){
            $sql = 'SELECT fullname, gwa, created_at FROM students_unverified_entries WHERE course = :course ORDER BY gwa, created_at ASC LIMIT 1;';
        } else {
            $sql = 'SELECT fullname, gwa, created_at FROM students_unverified_entries WHERE course = :course AND year_level = :year_level ORDER BY gwa, created_at ASC LIMIT 1;';
        }
        $query = $this->database->connect()->prepare($sql);
        if($year_level !== NULL){
            $query->bindParam(':year_level', $year_level);
        }
        $query->bindParam(':course', $course);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetch(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function fetchYearLevels(){
        $sql = 'SELECT DISTINCT year_level FROM student_accounts;'; // TODO: Change to students_verified_entries table later
        $query = $this->database->connect()->prepare($sql);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }
    
    // RESULT MODELS
    public function saveEntryToDatabase($email, $gwa, $image_proof){
        $entry_exists = $this->_entryExists($email);
        if ($entry_exists === 'is_pending') {
            $sql = "UPDATE students_unverified_entries SET gwa = :gwa, image_proof = :image_proof WHERE email = :email";
            $query = $this->database->connect()->prepare($sql);
            $query->bindParam(':gwa', $gwa);
            $query->bindParam(':email', $email);
            $query->bindParam(':image_proof', $image_proof);
        } else {
            if($entry_exists === 'is_verified'){
                $this->_deleteStudentVerifiedEntry($email);
            }
            $sql = "INSERT INTO students_unverified_entries(student_id, email, fullname, course, year_level, section, gwa, image_proof, submission_id)
            VALUES(:student_id, :email, :fullname, :course, :year_level, :section, :gwa, :image_proof, :submission_id)";
            $student = $this->_getStudentData($email);
            $adviser = $this->_getStudentAdviser($email);
            $course = $this->_getStudentCourse($email);
            $submission_id = $this->_getEntryTermSubmitted();
            $student_fullname = $student['last_name'] . ', ' . $student['first_name'] . ' ' . $student['middle_name'];

            $query = $this->database->connect()->prepare($sql);
            $query->bindParam(':student_id', $student['student_id']);
            $query->bindParam(':email', $email);
            $query->bindParam(':fullname', $student_fullname);
            $query->bindParam(':course', $course['course']);
            $query->bindParam(':year_level', $student['year_level']);
            $query->bindParam(':section', $student['section']);
            $query->bindParam(':gwa', $gwa);
            $query->bindParam(':image_proof', $image_proof, PDO::PARAM_LOB);
            $query->bindParam(':submission_id', $submission_id['submission_id']);
        }
        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getStudentSubmittedGWA($email){
        $sql = 'SELECT gwa FROM students_verified_entries WHERE email = :email';
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        }
    }

    private function _entryExists($email){
        if($this->_isEntryVerified($email)){
            $result = 'is_verified';
        } else if($this->_isEntryPending($email)){
            $result = 'is_pending';
        } else {
            $result = 'not_submitted';
        }

        return $result;
    }

    private function _deleteStudentVerifiedEntry($email){
        $sql = 'DELETE FROM students_verified_entries WHERE email = :email;';
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        return $query->execute();
    }

    private function _getStudentData($email){
        $this->_updateStudentStatus($email);
        $sql = "SELECT * FROM student_accounts WHERE email = :email LIMIT 1;";
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

    private function _getStudentAdviser($email){
		$sql = "SELECT sa.adviser_id as adviser_id, CONCAT(a.first_name, ', ', a.last_name, ' ', a.middle_name) as full_name
		FROM student_accounts as sa LEFT JOIN advisers as a ON sa.adviser_id = a.adviser_id
		WHERE sa.email = :email;";
		$query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$data=NULL;
		if($query->execute()){
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data;
		} else {
			return false;
		}
	}

    private function _getStudentCourse($email){
        $sql = 'SELECT c.course as course 
        FROM student_accounts AS sa
        LEFT JOIN courses AS c
        ON sa.course_id = c.course_id
        WHERE sa.email = :email;';
        $query = $this->database->connect()->prepare($sql);
		$query->bindParam(':email', $email);
		$data=NULL;
		if($query->execute()){
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data;
		} else {
			return false;
		}
    }

    private function _getEntryTermSubmitted(){
        $sql = 'SELECT submission_id FROM gwa_submission_schedule WHERE active = 1;';
        $query = $this->database->connect()->prepare($sql);
		$data=NULL;
		if($query->execute()){
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data;
		} else {
			return false;
		}
    }

    // public function setScreenshotFile($student_id, $image){
    //     if($this->screenshotFileExists($student_id)){
    //         $sql = "UPDATE Image_Proofs SET image = :image WHERE student_id = :student_id";
    //     } else {
    //         $sql = "INSERT INTO Image_Proofs(student_id, image) VALUES(:student_id, :image);";
    //     }
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(":student_id", $student_id);
    //     $query->bindParam(":image", $image);
    //     return $query->execute();
    // }

    // private function screenshotFileExists($student_id){
    //     $sql = "SELECT COUNT(*) FROM Image_Proofs WHERE student_id = :student_id;";
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(":student_id", $student_id);
    //     if($query->execute()){
    //         $row_count = $query->fetchColumn();
    //         return $row_count > 0;
    //     } else {
    //         return false;
    //     }
    // }

    // ======== DUMPS BUT MIGHT BE USEFUL ========

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

}


?>