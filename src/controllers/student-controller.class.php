<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);


class StudentController {
    private $allowedPages = ['home', 'about', 'profile', 'contact'];

    public function loadPage($page) {
        $file_path = "../resources/views/student/{$page}";
        if (file_exists($file_path)) {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
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

    public function loadMainPage() {
        $page = 'home';

        $templatePath = '../resources/views/student/' . $page . '.php';

        if (file_exists($templatePath)) {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
                unset($_SESSION['is-logged-in']);
                $_SESSION['action'] = 'logout';
                header('Location: ' . FRONT_DIR);
                exit;
            } else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['page'])) {
                $page = htmlspecialchars($_GET['page']);
                $filePath = '../resources/views/student' . $page . '.php';

                if (file_exists($filePath)) {
                    echo file_get_contents($filePath);
                    exit; // End script after handling AJAX request
                } else {
                    echo '<h2>404 Not Found</h2><p>The page you are looking for does not exist.</p>';
                    exit; // End script after handling AJAX request
                }
}
            include $templatePath;
        } else {
            echo '<h2>404 Not Found</h2><p>The page you are looking for does not exist.</p>';
        }
    }

    public function loadPage1($page) {
        if (in_array($page, $this->allowedPages)) {
            // Check if a page was set in the session
            if (isset($_SESSION['currentPage'])) {
                $page = $_SESSION['currentPage'];
            } else {
                $_SESSION['currentPage'] = 'main'; // Default to 'main'
                echo $_SESSION['currentPage'];
            }

            // Construct file path
            $filePath = "../resources/views/student/{$page}.php";

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
                unset($_SESSION['is-logged-in']);
                session_regenerate_id(true); // Regenerate session ID to prevent fixation
                $_SESSION['action'] = 'logout';
                header('Location: ' . FRONT_DIR);
                exit;
            }

            // Check if the file exists before including it
            if (file_exists($filePath)) {
                require_once($filePath);
            } else {
                $this->handleError('Page not found.');
            }
        } else {
            $this->handleError('Invalid page requested.');
        }

        // Update currentPage if a valid GET parameter is present
        if (isset($_GET['page']) && in_array($_GET['page'], $this->allowedPages)) {
            $_SESSION['currentPage'] = $_GET['page'];
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
