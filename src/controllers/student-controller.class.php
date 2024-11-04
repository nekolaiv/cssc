<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);


class StudentController {
    private $allowedPages = ['home', 'about', 'profile', 'contact'];

    public function loadPage($page) {
        // print_r($_GET);
        $file_path = "../resources/views/student/{$page}";
        if (file_exists($file_path)) {
            // if (isset($_GET['action'])) {
            //     if ($_GET['action'] === 'saveCourse') {
            //         echo($this->saveCourse());
            //     }
            // }   

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                if(isset($_POST['logout'])){
                    unset($_SESSION['is-logged-in']);
                    $_SESSION['action'] = 'logout';
                    header('Location: ' . FRONT_DIR);
                    exit;
                } 
                
            } else if($_SERVER['REQUEST_METHOD'] === 'GET'){
                
                
            }

            $this->addCourse();
            include_once($file_path);

        } else {
            echo "404 Not Found";
        }
    }

    public function addCourse() {
    // Initialize session subjects if not set
    if (!isset($_SESSION['subjects'])) {
        $_SESSION['subjects'] = [['subject-code' => '', 'unit' => '', 'grade' => '']];
    }

    // Handle POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;

        if ($action === 'add-subject') {
            $_SESSION['subjects'][] = ['subject-code' => '', 'unit' => '', 'grade' => ''];
            $this->redirect();
        }

        if ($action === 'save-courses' && isset($_POST['subjects'])) {
            foreach ($_POST['subjects'] as $index => $subject_data) {
                // Basic validation
                if (isset($subject_data['subject-code'], $subject_data['unit'], $subject_data['grade'])) {
                    $_SESSION['subjects'][$index] = [
                        'subject-code' => htmlspecialchars($subject_data['subject-code']),
                        'unit' => htmlspecialchars($subject_data['unit']),
                        'grade' => htmlspecialchars($subject_data['grade']),
                    ];
                }
            }
            $this->redirect();
        }

        if (isset($_POST['remove-subject'])) {
            $index = intval($_POST['remove-subject']);
            if (isset($_SESSION['subjects'][$index])) {
                unset($_SESSION['subjects'][$index]);
                $_SESSION['subjects'] = array_values($_SESSION['subjects']);
            }
            $this->redirect();
        }
    }
}

private function redirect() {
    header('Location: ./index.php');
    exit;
}


    public function saveCourse() {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect the HTML from the POST request
            $courseHTML = $_POST['courseHTML'] ?? null;

            // Validate the input data
            if ($courseHTML) {
                // Initialize the session array if it doesn't exist
                if (!isset($_SESSION['courses'])) {
                    $_SESSION['courses'] = [];
                }

                // Add the new course HTML to the session array
                $_SESSION['courses'][] = $courseHTML;

                // Optionally return the updated session data or a success message
                echo json_encode(['success' => true, 'courses' => $_SESSION['courses']]);
                return;
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Invalid input']);
                return;
            }
        }

        // Handle other request types if necessary
        http_response_code(405); // Method Not Allowed
    }


        public function handleRequest() {
        session_start(); // Start the session

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo $_GET['action'];
            if (isset($_GET['action']) && $_GET['action'] === 'save-course') {
                $this->saveCourseToSession();
            } else {
                $this->calculateGPA();
            }
        } else {
            $this->showForm();
        }
    }

    private function showForm() {
        // Fetch courses from the session
        $courses = $_SESSION['courses'] ?? [];
        require_once '../resources/student/course_form.php';
    }

    private function saveCourseToSession() {
        echo "saved courses to session";
        print_r($_SESSION['courses']);
        // Validate and save course data to session
        $subjectCode = $_POST['subjectCode'];
        $units = $_POST['units'];
        $grades = $_POST['grades'];

        // Initialize session array if not set
        if (!isset($_SESSION['courses'])) {
            $_SESSION['courses'] = [];
        }

        $_SESSION['courses'][] = [
            'subjectCode' => $subjectCode,
            'units' => $units,
            'grades' => $grades
        ];
    }

//     public function loadMainPage() {
//         $page = 'home';

//         $templatePath = '../resources/views/student/' . $page . '.php';

//         if (file_exists($templatePath)) {
//             if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
//                 unset($_SESSION['is-logged-in']);
//                 $_SESSION['action'] = 'logout';
//                 header('Location: ' . FRONT_DIR);
//                 exit;
//             } else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['page'])) {
//                 $page = htmlspecialchars($_GET['page']);
//                 $filePath = '../resources/views/student' . $page . '.php';

//                 if (file_exists($filePath)) {
//                     echo file_get_contents($filePath);
//                     exit; // End script after handling AJAX request
//                 } else {
//                     echo '<h2>404 Not Found</h2><p>The page you are looking for does not exist.</p>';
//                     exit; // End script after handling AJAX request
//                 }
// }
//             include $templatePath;
//         } else {
//             echo '<h2>404 Not Found</h2><p>The page you are looking for does not exist.</p>';
//         }
//     }

//     public function loadPage1($page) {
//         if (in_array($page, $this->allowedPages)) {
//             // Check if a page was set in the session
//             if (isset($_SESSION['currentPage'])) {
//                 $page = $_SESSION['currentPage'];
//             } else {
//                 $_SESSION['currentPage'] = 'main'; // Default to 'main'
//                 echo $_SESSION['currentPage'];
//             }

//             // Construct file path
//             $filePath = "../resources/views/student/{$page}.php";

//             if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
//                 unset($_SESSION['is-logged-in']);
//                 session_regenerate_id(true); // Regenerate session ID to prevent fixation
//                 $_SESSION['action'] = 'logout';
//                 header('Location: ' . FRONT_DIR);
//                 exit;
//             }

//             // Check if the file exists before including it
//             if (file_exists($filePath)) {
//                 require_once($filePath);
//             } else {
//                 $this->handleError('Page not found.');
//             }
//         } else {
//             $this->handleError('Invalid page requested.');
//         }

//         // Update currentPage if a valid GET parameter is present
//         if (isset($_GET['page']) && in_array($_GET['page'], $this->allowedPages)) {
//             $_SESSION['currentPage'] = $_GET['page'];
//         }
//     }


    

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
