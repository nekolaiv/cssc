<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}

require_once('../config/config.php');
require_once('../src/controllers/auth-controller.class.php');
require_once('../src/controllers/student-controller.class.php');

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
                break;

            case 'register':
                $this->auth_controller->register();
                break;

            case 'forgot-password':
                $this->auth_controller->forgotPassword();
                break;

            case 'logout':
                $this->auth_controller->logout();
                break;

            default:
                header('Location: ./index.php', true, 302);
                exit;
        }
    }

    private function handleAuthenticatedUser() {
        $user_type = $_SESSION['user-type'];
        switch ($user_type) {
            case 'student':
                $page = isset($_GET['page']) ? $_GET['page'] : 'main';
                $this->student_controller->loadPage($page);
                break;

            case 'staff':
            case 'admin':
                require_once(STUDENT_DIR . 'home.php');
                break;
        }
    }
}

$frontController = new FrontController();
$frontController->run();

?>
