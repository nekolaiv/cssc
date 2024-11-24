<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../src/classes/student.class.php');
require_once('../src/middlewares/student-middleware.class.php');

use Src\Classes\Student;
use Src\Middlewares\StudentMiddleware;

class StudentController
{

    private $allowedPages = ['home', 'about', 'profile', 'contact'];
    private $student;
    private $middleware;
    private $root_directory;

    public function __construct()
    {
        $this->middleware = new StudentMiddleware();
        $this->root_directory = dirname(__FILE__, 3);
        $this->student = new Student();
    }

    public function loadPage($page, $action = NULL, $post = NULL)
    {
        $file_path = "../resources/views/student/{$page}";
        if (file_exists($file_path)) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout']) && $_POST['logout'] === 'logout') {
                unset($_SESSION['is-logged-in']);
                $_SESSION['action'] = 'logout';
                header('Location: ' . FRONT_DIR);
                exit;
            }

            if ($action === 'show-leaderboard') {
                $cs_top1 = $this->student->getCSTopNotcher();
                $it_top1 = $this->student->getITTopNotcher();
                $act_top1 = $this->student->getACTTopNotcher();
                $cs_leaderboard = $this->student->getCSLeaderboardData();
                $it_leaderboard = $this->student->getITLeaderboardData();
                $act_leaderboard = $this->student->getACTLeaderboardData();
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate-GWA']) && $_POST['calculate-GWA'] === 'calculate-GWA') {
                $subject_codes = $_SESSION['course-fields']['subject-code'];
                $grades = $_SESSION['course-fields']['grade'];
                $units = $_SESSION['course-fields']['unit'];
                for ($i = 0; $i < count($_SESSION['course-fields']['subject-code']); $i++) {
                    if ($_SESSION['course-fields']['subject-code'][$i] == NULL || $_SESSION['course-fields']['grade'][$i] == NULL || $_SESSION['course-fields']['unit'][$i] == NULL) {
                        unset($_SESSION['course-fields']['subject-code'][$i]);
                        unset($_SESSION['course-fields']['grade'][$i]);
                        unset($_SESSION['course-fields']['unit'][$i]);
                    }
                }

                for ($i = 0; $i < count($subject_codes); $i++) {
                    if ($subject_codes[$i] !== NULL && $grades[$i] !== NULL && $units[$i] !== NULL) {
                        $subject_codes[$i] = $this->middleware->cleanInput($subject_codes[$i]);
                        $grades[$i] = $this->middleware->cleanNumericInput($grades[$i]);
                        $units[$i] = $this->middleware->cleanNumericInput($units[$i]);
                    }
                }

                $gwa_result = $this->student->calculateGWA($subject_codes, $grades, $units);
                $student_entry = $this->student->getStudentEntry($_SESSION['profile']['email']);

                if ($gwa_result >= 1 && $gwa_result <= 2) {
                    $_SESSION['GWA'] = ['message-1' => 'Congratulations!', 'message-2' => 'You are qualified for:', 'message-3' => "Dean's Lister", 'gwa-score' => $gwa_result];
                } else if ($gwa_result > 2 && $gwa_result <= 5) {
                    $_SESSION['GWA'] = ['message-1' => "We're sorry", 'message-2' => 'You not are qualified for:', 'message-3' => "Dean's Lister", 'gwa-score' => $gwa_result];
                } else {
                    $_SESSION['GWA'] = ['message-1' => "Invalid Grade", 'message-2' => 'There must be a mistake with your inputs', 'message-3' => "Edit Inputs to Double Check", 'gwa-score' => $gwa_result];
                }
                // TODO create submit result for validation feature in result page and use this function

            }

            if ($page === 'results.php') {
                $validate_button = "Validate Entry";
            }

            if (isset($_POST['submit-entry']) && $_POST['submit-entry'] === 'submit-calculation') {
                echo "hello";
                $submission_status = $this->student->saveEntryToDatabase($_SESSION['profile']['email'], $gwa_result);
                if ($submission_status) {
                    $validate_button = "Submitted";
                }
            }
            include_once($file_path);
        } else {
            echo "404 Not Found";
        }
    }
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Instantiate the handler class
    
//     $controller = new StudentController();

//     $controller->loadPage('results.php');

// } else {
//     // If it's not a POST request, return an error
//     // echo json_encode(["error" => "Invalid request method."]);
// }
?>