<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../src/classes/student.class.php');

use Src\Classes\Student;

class StudentController {

    private $allowedPages = ['home', 'about', 'profile', 'contact'];
    private $student;

    public function __construct(){
        $this->student = new Student();
    }

    public function loadPage($page) {
        $file_path = "../resources/views/student/{$page}";
        if (file_exists($file_path)) {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout']) && $_POST['logout'] === 'logout'){
                unset($_SESSION['is-logged-in']);
                $_SESSION['action'] = 'logout';
                header('Location: ' . FRONT_DIR);
                exit;
            } 
            
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate-GWA']) && $_POST['calculate-GWA'] === 'calculate-GWA'){
                $gwa_result = $this->student->calculateGWA();
                if ($gwa_result[0] === true){
                    $_SESSION['GWA'] = ['message-1' => 'Congratulations!', 'message-2' => 'You are qualified for:', 'message-3' => "Dean's Lister", 'gwa-score' => $gwa_result[1]];
                } else {
                    $_SESSION['GWA'] = ['message-1' => "We're sorry", 'message-2' => 'You not are qualified for:', 'message-3' => "Dean's Lister", 'gwa-score' => $gwa_result[1]];
                }
                $this->student->saveEntryToDatabase($_SESSION['profile']['email'], $gwa_result[1]);
            }
            include_once($file_path);
        } else {
            echo "404 Not Found";
        }
    }
}
?>
