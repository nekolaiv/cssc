<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}

ob_start();

// ================
// TESTING PURPOSES
// print_r($_POST);
// print_r($_SESSION);
// echo 'outside';
// ================
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once('../config/config.php');
require_once('../src/controllers/auth-controller.class.php');
require_once('../src/controllers/student-controller.class.php');

// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// header("Cache-Control: post-check=0, pre-check=0", false);
// header("Pragma: no-cache");

use Src\Controllers\AuthController;
use Src\Controllers\StudentController;

class FrontController {
    private $auth_controller;
    private $student_controller;

    public function __construct() {
        $this->auth_controller = new AuthController();
        $this->student_controller = new StudentController();
    }

    public function run() {
        if ($this->isRouteAjaxRequest()) {
            $this->handleRouteAjaxRequest();
            return; // Exit after handling AJAX
        }

        if (empty($_SESSION['action'])) {
            $_SESSION['action'] = 'login';
            header('Refresh: 0');
            exit;
        }

        if (empty($_SESSION['is-logged-in'])) {
            $this->handleUnauthenticatedUser();
        } else if ($_SESSION['is-logged-in'] === true && isset($_SESSION['user-id'])) {
            $this->handleAuthenticatedUser();
        }
    }

    private function handleUnauthenticatedUser() {
        $action = $_SESSION['action'];
        switch ($action) {
            case 'login':
                $this->auth_controller->login();
                return;

            case 'register':
                $this->auth_controller->register();
                return;

            case 'forgot-password':
                $this->auth_controller->forgotPassword();
                return;

            case 'logout':
                $this->auth_controller->logout();
                return;

            default:
                header('Location: ./index.php');
                exit;
        }
    }

    private function handleAuthenticatedUser() {
        $user_type = $_SESSION['user-type'];
        switch ($user_type) {
            case 'student':
                $this->student_controller->loadPage('main.php');
                return;

            case 'staff':
            case 'admin':
                require_once(STUDENT_DIR . 'home.php');
                break;
        }
    }

    private function isRouteAjaxRequest() {
        return isset($_GET['page']);
    }

    private function handleRouteAjaxRequest(){
        // $_SESSION['current-page'] = isset($_GET['page']) ? $_GET['page'] : '';
        // $page = $_GET['page'] ?? 'home';
        if (isset($_GET['page'])) {
            if($_GET['page'] === 'leaderboard.php'){
                $this->student_controller->loadPage($_GET['page'], "show-leaderboard");
            } else {
                $this->student_controller->loadPage($_GET['page']);
            }
            
        }
        
    }

    // private function handleAjaxRequest1() {
    //     $action = $_GET['action'] ?? null;
    //     if (empty($_SESSION['is-logged-in'])) {
    //         echo json_encode(['error' => 'Unauthorized']);
    //         http_response_code(401);
    //         return;
    //     }

    //     // Handle AJAX actions based on user type
    //     $user_type = $_SESSION['user-type'];
    //     switch ($user_type) {
    //         case 'student':
    //             // Call the appropriate method in StudentController
    //             if ($action === 'getProfile') {
    //                 $this->student_controller->getProfile();
    //             }
    //             break;
    //         case 'staff':
    //         case 'admin':
    //             // Handle staff/admin AJAX requests here
    //             break;
    //         default:
    //             echo json_encode(['error' => 'Invalid user type']);
    //             http_response_code(403);
    //     }
    // }
}

$frontController = new FrontController();
$frontController->run();

ob_end_flush();

?>
