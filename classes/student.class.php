<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/database.class.php');
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
        LEFT JOIN prospectus as p ON c.id = p.curriculum_id
        LEFT JOIN dean_lister_application_periods as dlap ON dlap.status = 'open'
        WHERE u.id = :id AND (CAST(RIGHT(dlap.year, 4) AS SIGNED) - CAST(LEFT(u.identifier, 4) AS SIGNED)) = p.year_level
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
        CONCAT(u.lastname, ', ', u.firstname, ' ', u.middlename) AS fullname,
        sa.total_rating AS total_rating
        FROM
            student_applications AS sa
        LEFT JOIN
            dean_lister_application_periods AS dlap1 ON dlap1.status = 'open'
        LEFT JOIN
            user AS u ON sa.user_id = u.id
        LEFT JOIN
            course AS c ON u.department_id = c.id
        WHERE 
            (CAST(RIGHT(dlap1.year, 4) AS SIGNED) - CAST(LEFT(u.identifier, 4) AS SIGNED)) = :year_level
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
    public function saveEntryToDatabase($gwa, $image_proof){
        $user_id = cleanInput($_SESSION['profile']['user-id']);
        $entry_exists = $this->_entryExists($user_id);
        if($entry_exists){
            $this->_deleteStudentVerifiedEntry($user_id);
        }
        $sql = "INSERT INTO student_applications(id, user_id, adviser_id, school_year, semester, total_rating, dean_lister_period_id, image_proof)
        VALUES(:id, :user_id, :adviser_id, :school_year, :semester, :total_rating, :dean_lister_period_id, :image_proof)";
        
        $current_term = $this->_getCurrentAcademicTerm();

        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':id', $_SESSION['profile']['user-id']);
        $query->bindParam(':user_id', $_SESSION['profile']['user-id']);
        $query->bindParam(':adviser_id', $_SESSION['profile']['user-id']);
        $query->bindParam(':school_year', $_SESSION['profile']['school-year']);
        $query->bindParam(':semester', $current_term['semester']);
        $query->bindParam(':total_rating', $gwa);
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
    

    public function getStudentApplication($id){
        $sql = 'SELECT total_rating, status FROM student_applications WHERE user_id = :id';
        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':id', $id);
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
}
?>