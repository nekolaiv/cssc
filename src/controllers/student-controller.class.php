<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class StudentController {
    private $allowedPages = ['home', 'about', 'profile', 'contact'];

    public function loadPage($page) {
        $file_path = "../resources/views/student/{$page}";
        if (file_exists($file_path)) {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout']) && $_POST['logout'] === 'logout'){
                unset($_SESSION['is-logged-in']);
                $_SESSION['action'] = 'logout';
                header('Location: ' . FRONT_DIR);
                exit;
            }
            include_once($file_path);
        } else {
            echo "404 Not Found";
        }
    }
}
?>
