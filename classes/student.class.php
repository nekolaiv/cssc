<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("database.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/clean.function.php');

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

    // ASSIGNING STUDENT CURRICULAR SUBJECTS
    public function loadStudentsSubjects($id){
        $sql = "SELECT (CAST(RIGHT(dlap.year, 4) AS SIGNED) - CAST(LEFT(u.identifier, 4) AS SIGNED)) AS student_year, 
        p.descriptive_title as subject_name, p.subject_code as subject_code, p.total_units as units
        FROM user as u
        LEFT JOIN curriculum as c ON u.curriculum_id = c.id
        LEFT JOIN dean_lister_application_periods as dlap ON dlap.status = 'open'
        LEFT JOIN prospectus as p ON c.id = p.curriculum_id
        WHERE u.id = :id AND CAST(RIGHT(dlap.year, 4) AS SIGNED) - CAST(LEFT(u.identifier, 4) AS SIGNED) = p.year_level
        AND dlap.semester = p.semester";
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    // LEADERBOARD MODELS

    public function loadLeaderboard(){
        $sql = "SELECT
        u.identifier AS student_id,
        CONCAT(u.lastname, ', ', u.firstname, ' ', u.middlename) AS fullname,
        sa.total_rating AS total_rating,
        c.course_name AS course,
        (CAST(RIGHT(dlap1.year, 4) AS SIGNED) - CAST(LEFT(u.identifier, 4) AS SIGNED)) AS year_level,
        CONCAT(dlap2.year, ' - ', dlap2.semester) AS submission_description
        FROM
            student_applications AS sa
        LEFT JOIN
            dean_lister_application_periods AS dlap1 ON dlap1.status = 'open'
        LEFT JOIN
            dean_lister_application_periods AS dlap2 ON sa.dean_lister_period_id = dlap2.id
        LEFT JOIN
            user AS u ON sa.user_id = u.id
        LEFT JOIN
            course AS c ON u.department_id = c.id
        WHERE 
            sa.total_rating <= 2.0 
            AND sa.status = 'Approved'
        ORDER BY sa.total_rating, CONCAT(u.lastname, ', ', u.firstname, ' ', u.middlename) ASC
        ";
        $query = $this->database->connect()->prepare($sql);
        if ($query->execute()) {
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function getStudentTopNotcher($year_level = NULL, $course = NULL) {
        $sql = "SELECT
        u.identifier AS student_id,
        CONCAT(u.lastname, ', ', u.firstname, ' ', u.middlename) AS fullname,
        sa.total_rating AS total_rating,
        c.course_name AS course,
        (CAST(RIGHT(dlap1.year, 4) AS SIGNED) - CAST(LEFT(u.identifier, 4) AS SIGNED)) AS year_level,
        CONCAT(dlap2.year, ' - ', dlap2.semester) AS submission_description
        FROM
            student_applications AS sa
        LEFT JOIN
            dean_lister_application_periods AS dlap1 ON dlap1.status = 'open'
        LEFT JOIN
            dean_lister_application_periods AS dlap2 ON sa.dean_lister_period_id = dlap2.id
        LEFT JOIN
            user AS u ON sa.user_id = u.id
        LEFT JOIN
            course AS c ON u.department_id = c.id
        WHERE 
            (CAST(RIGHT(dlap2.year, 4) AS SIGNED) - CAST(LEFT(u.identifier, 4) AS SIGNED)) = :year_level
            AND u.department_id = :course
            AND sa.total_rating <= 2.0 
            AND sa.status = 'Approved'
        ORDER BY sa.total_rating, CONCAT(u.lastname, ', ', u.firstname, ' ', u.middlename) ASC LIMIT 1;
        ";
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':year_level', $year_level);
        $query->bindParam(':course', $course);
        if ($query->execute()) {
            return $query->fetch(PDO::FETCH_ASSOC); 
        } else {
            return false;
        }
    }

    public function fetchCourses(){
        $sql = "SELECT DISTINCT c.course_name AS course
        FROM student_applications AS sa
        LEFT JOIN user AS u ON sa.user_id = u.identifier
        LEFT JOIN course AS c ON u.department_id = c.id;";
        $query = $this->database->connect()->prepare($sql);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }
    
    public function fetchYearLevels(){
        $sql = "SELECT DISTINCT (CAST(RIGHT(dlap.year, 4) AS SIGNED) - CAST(LEFT(u.identifier, 4) AS SIGNED)) AS year_level
        FROM dean_lister_application_periods AS dlap
        LEFT JOIN user AS u ON dlap.status = 'open'
        WHERE u.identifier NOT LIKE '0000%';";
        $query = $this->database->connect()->prepare($sql);
        $data=NULL;
        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $data;
        } else {
            return false;
        }
    }

    public function fetchSubmissionId(){
        $sql = "SELECT DISTINCT sa.dean_lister_period_id AS submission_id, CONCAT(dlap.year,' - ', dlap.semester) AS submission_description
        FROM student_applications AS sa
        LEFT JOIN dean_lister_application_periods AS dlap ON sa.dean_lister_period_id = dlap.id;"; // TODO: Change to students_verified_entries table later
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
        $user_id = cleanInput($_SESSION['profile']['student-id']);
        $entry_exists = $this->_entryExists($user_id);
        if($entry_exists){
            $this->_deleteStudentVerifiedEntry($user_id);
        }
        $sql = "INSERT INTO student_applications(user_id, adviser_id, school_year, semester, total_rating, dean_lister_period_id, image_proof)
        VALUES(:user_id, :adviser_id, :school_year, :semester, :total_rating, :dean_lister_period_id, :image_proof)";
        
        $current_term = $this->_getCurrentAcademicTerm();

        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':user_id', $_SESSION['profile']['student-id']);
        $query->bindParam(':adviser_id', $_SESSION['profile']['user-id']);
        $query->bindParam(':school_year', $_SESSION['profile']['school-year']);
        $query->bindParam(':semester', $current_term['semester']);
        $query->bindParam(':total_rating', $_SESSION['GWA']['gwa-score']);
        $query->bindParam(':dean_lister_period_id', $current_term['id']);
        $query->bindParam(':image_proof', $image_proof, PDO::PARAM_LOB);
        
        if ($query->execute()) {
            return true;
        } else {
            return false;
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

    private function _entryExists($user_id){
        $sql = "SELECT COUNT(*) FROM student_applications WHERE user_id = :user_id";
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':user_id', $user_id);
        if($query->execute()){
            $row_count = $query->fetchColumn();
            return $row_count > 0;
        }
    }


    private function _deleteStudentVerifiedEntry($user_id){
        $sql = 'DELETE FROM student_applications WHERE user_id = :user_id;';
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':user_id', $user_id);
        return $query->execute();
    }

    

    // ======== DUMPS BUT MIGHT BE USEFUL ========

    // public function getCSTopNotcher($year_level = NULL){
    //     if($year_level === NULL){
    //         $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "1" ORDER BY gwa, created_at ASC LIMIT 1;';
    //     } else {
    //         $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "1" AND year_level = :year_level ORDER BY gwa, created_at ASC LIMIT 1;';
    //     }
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(':year_level', $year_level);
    //     $data=NULL;
    //     if($query->execute()){
    //         $data = $query->fetch(PDO::FETCH_ASSOC); 
    //         return $data;
    //     } else {
    //         return false;
    //     }
    // }

    // public function getITTopNotcher($year_level = NULL){
    //     if($year_level === NULL){
    //         $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "2" ORDER BY gwa, created_at ASC LIMIT 1;';
    //     } else {
    //         $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "2" AND year_level = :year_level ORDER BY gwa, created_at ASC LIMIT 1;';
    //     }
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(':year_level', $year_level);
    //     $data=NULL;
    //     if($query->execute()){
    //         $data = $query->fetch(PDO::FETCH_ASSOC); 
    //         return $data;
    //     } else {
    //         return false;
    //     }
    // }

    // public function getACTTopNotcher($year_level = NULL){
    //     if($year_level === NULL){
    //         $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "3" ORDER BY gwa, created_at ASC LIMIT 1;';
    //     } else {
    //         $sql = 'SELECT fullname, gwa, created_at FROM students_verified_entries WHERE course = "3" AND year_level = :year_level ORDER BY gwa, created_at ASC LIMIT 1;';
    //     }
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(':year_level', $year_level);
    //     $data=NULL;
    //     if($query->execute()){
    //         $data = $query->fetch(PDO::FETCH_ASSOC); 
    //         return $data;
    //     } else {
    //         return false;
    //     }
    // }


    // public function getCSLeaderboardData($year_level = NULL){
    //     if($year_level === NULL){
    //         $sql = 'SELECT * FROM students_verified_entries WHERE course = "1" ORDER BY gwa, fullname ASC';
    //     } else {
    //         $sql = 'SELECT * FROM students_verified_entries WHERE course = "1" AND year_level = :year_level ORDER BY gwa, fullname ASC';
    //     }
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(':year_level', $year_level);
    //     $data=NULL;
    //     if($query->execute()){
    //         $data = $query->fetchAll(PDO::FETCH_ASSOC); 
    //         return $data;
    //     } else {
    //         return false;
    //     }
    // }

    // public function getITLeaderboardData($year_level = NULL){
    //     if($year_level === NULL){
    //         $sql = 'SELECT * FROM students_verified_entries WHERE course = "2" ORDER BY gwa, fullname ASC';
    //     } else {
    //         $sql = 'SELECT * FROM students_verified_entries WHERE course = "2" AND year_level = :year_level ORDER BY gwa, fullname ASC';
    //     }
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(':year_level', $year_level);
    //     $data=NULL;
    //     if($query->execute()){
    //         $data = $query->fetchAll(PDO::FETCH_ASSOC); 
    //         return $data;
    //     } else {
    //         return false;
    //     }
    // }

    // public function getACTLeaderboardData($year_level = NULL){
    //     if($year_level === NULL){
    //         $sql = 'SELECT * FROM students_verified_entries WHERE course = "3" ORDER BY gwa, fullname ASC';
    //     } else {
    //         $sql = 'SELECT * FROM students_verified_entries WHERE course = "3" AND year_level = :year_level ORDER BY gwa, fullname ASC';
    //     }
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(':year_level', $year_level);
    //     $data=NULL;
    //     if($query->execute()){
    //         $data = $query->fetchAll(PDO::FETCH_ASSOC); 
    //         return $data;
    //     } else {
    //         return false;
    //     }
    // }

    // private function _studentUnverifiedEntryExists($email){
    //     $sql = "SELECT COUNT(*) FROM students_unverified_entries WHERE email = :email LIMIT 1;";
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(':email', $email);
    //     if ($query->execute()) {
    //         $row_count = $query->fetchColumn();
    //         return $row_count > 0;
    //     } else {
    //         return false;
    //     }
    // }

    // private function _studentVerifiedEntryExists($email){
    //     $sql = "SELECT COUNT(*) FROM students_verified_entries WHERE email = :email LIMIT 1;";
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(':email', $email);
    //     if ($query->execute()) {
    //         $row_c`ount = $query->fetchColumn();
    //         return $row_count > 0;
    //     } 
    //     return false;
    // }

    // private function getStudentEntry($email){
    //     if($this->_studentUnverifiedEntryExists($email)){
    //         $sql = "SELECT * FROM students_unverified_entries WHERE email = :email LIMIT 1;";
    //     } else if ($this->_studentVerifiedEntryExists($email)){
    //         $sql = "SELECT * FROM students_verified_entries WHERE email = :email LIMIT 1;";
    //     }
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(':email', $email);
    //     $data = NULL;
    //     if ($query->execute()) {
    //         $data = $query->fetch(PDO::FETCH_ASSOC);
    //         return $data;
    //     } else {
    //         return false;
    //     }
    // }

    // public function getStudentImageProof( $email ){
    //     $data = $this->getStudentEntry($email);
    //     return $data['image_proof'];
    // }


    // public function saveEntryToDatabase($email, $gwa, $image_proof){
    //     $entry_exists = $this->_entryExists($email);
    //     if ($entry_exists === 'is_pending') {
    //         $sql = "UPDATE students_unverified_entries SET total_rating = :gwa, image_proof = :image_proof WHERE email = :email";
    //         $query = $this->database->connect()->prepare($sql);
    //         $query->bindParam(':gwa', $gwa);
    //         $query->bindParam(':email', $email);
    //         $query->bindParam(':image_proof', $image_proof);
    //     } else {
    //         if($entry_exists === 'is_verified'){
    //             $this->_deleteStudentVerifiedEntry($email);
    //         }
    //         $sql = "INSERT INTO students_unverified_entries(student_id, email, fullname, course, year_level, section, gwa, image_proof, submission_id)
    //         VALUES(:student_id, :email, :fullname, :course, :year_level, :section, :gwa, :image_proof, :submission_id)";
    //         $student = $this->_getStudentData($email);
    //         $adviser = $this->_getStudentAdviser($email);
    //         $course = $this->_getStudentCourse($email);
    //         $submission_id = $this->_getEntryTermSubmitted();
    //         $student_fullname = $student['last_name'] . ', ' . $student['first_name'] . ' ' . $student['middle_name'];

    //         $query = $this->database->connect()->prepare($sql);
    //         $query->bindParam(':student_id', $student['student_id']);
    //         $query->bindParam(':email', $email);
    //         $query->bindParam(':fullname', $student_fullname);
    //         $query->bindParam(':course', $course['course']);
    //         $query->bindParam(':year_level', $student['year_level']);
    //         $query->bindParam(':section', $student['section']);
    //         $query->bindParam(':gwa', $gwa);
    //         $query->bindParam(':image_proof', $image_proof, PDO::PARAM_LOB);
    //         $query->bindParam(':submission_id', $submission_id['submission_id']);
    //     }
    //     if ($query->execute()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // private function _getStudentData($email){
    //     $this->_updateStudentStatus($email);
    //     $sql = "SELECT * FROM student_accounts WHERE email = :email LIMIT 1;";
    //     $query = $this->database->connect()->prepare($sql);
    //     $query->bindParam(':email', $email);
    //     $student = NULL;
    //     if ($query->execute()) {
    //         $student = $query->fetch(PDO::FETCH_ASSOC);
    //         return $student;
    //     } else {
    //         return false;
    //     }
    // }

    // private function _getStudentAdviser($email){
	// 	$sql = "SELECT sa.adviser_id as adviser_id, CONCAT(a.first_name, ', ', a.last_name, ' ', a.middle_name) as full_name
	// 	FROM student_accounts as sa LEFT JOIN advisers as a ON sa.adviser_id = a.adviser_id
	// 	WHERE sa.email = :email;";
	// 	$query = $this->database->connect()->prepare($sql);
	// 	$query->bindParam(':email', $email);
	// 	$data=NULL;
	// 	if($query->execute()){
	// 		$data = $query->fetch(PDO::FETCH_ASSOC);
	// 		return $data;
	// 	} else {
	// 		return false;
	// 	}
	// }

    // private function _getStudentCourse($email){
    //     $sql = 'SELECT c.course as course 
    //     FROM student_accounts AS sa
    //     LEFT JOIN courses AS c
    //     ON sa.course_id = c.course_id
    //     WHERE sa.email = :email;';
    //     $query = $this->database->connect()->prepare($sql);
	// 	$query->bindParam(':email', $email);
	// 	$data=NULL;
	// 	if($query->execute()){
	// 		$data = $query->fetch(PDO::FETCH_ASSOC);
	// 		return $data;
	// 	} else {
	// 		return false;
	// 	}
    // }

    // private function _getEntryTermSubmitted(){
    //     $sql = 'SELECT submission_id FROM gwa_submission_schedule WHERE active = 1;';
    //     $query = $this->database->connect()->prepare($sql);
	// 	$data=NULL;
	// 	if($query->execute()){
	// 		$data = $query->fetch(PDO::FETCH_ASSOC);
	// 		return $data;
	// 	} else {
	// 		return false;
	// 	}
    // }

    // private function _entryExists($email){
    //     if($this->_isEntryVerified($email)){
    //         $result = 'is_verified';
    //     } else if($this->_isEntryPending($email)){
    //         $result = 'is_pending';
    //     } else {
    //         $result = 'not_submitted';
    //     }

    //     return $result;
    // }

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