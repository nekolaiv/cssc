<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);


class StudentController {
    private $allowedPages = ['main', 'home', 'about', 'profile', 'contact'];

    public function loadPage($page='home') {
        // Sanitize input
        if (in_array($page, $this->allowedPages)) {
            $filePath = "../resources/views/student/{$page}.php";
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
                unset($_SESSION['is-logged-in']);
                $_SESSION['action'] = 'logout';
                header('Location: ' . FRONT_DIR);
                exit;
            } else {
                require_once($filePath);
            }
        } else {
            $this->handleError('Invalid page requested.');
        }
    }

    private function handleError($message) {
        echo "<p>Error: {$message}</p>";
    }
}

// Create an instance of the ContentLoader
// $contentLoader = new ContentLoader();
// $contentLoader->loadPage($page);



// class StudentController {

//     private $route;

//     public function __construct(){
//         $this->route = new RouteController(); 
//     }

//     public function routeUser($route='main'){
//         $route = isset($_GET['route']) ? $_GET['route'] : '';
//         switch($route) {
//             case 'main':
//                 $this->route->studentMainView();
//                 break;
//         }
//     }

//     public function mainView(){
//         if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
//             unset($_SESSION['is-logged-in']);
//             $_SESSION['action'] = 'logout';
//             header('Location: ' . FRONT_DIR);
//             exit;
//         } else {
//             require_once($filePath);
//         }
//     }

// }

?>
