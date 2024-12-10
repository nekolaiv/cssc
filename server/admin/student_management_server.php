<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "../../tools/clean.function.php";
require_once "../../classes/_admin.class.php";

// Instantiate Admin class
$admin = new Admin();

// Initialize response array
$response = ["success" => false, "message" => "", "data" => []];

// Determine the action
if (isset($_POST['action'])) {
    $action = cleanInput($_POST['action']);

    try {
        switch ($action) {
            case 'create':
                // Sanitize inputs
                $student_id = cleanNumericInput($_POST['student_id']);
                $first_name = cleanInput($_POST['first_name']);
                $middle_name = cleanInput($_POST['middle_name']);
                $last_name = cleanInput($_POST['last_name']);
                $email = cleanInput($_POST['email']);
                $password = cleanInput($_POST['password']);
                $course_id = cleanNumericInput($_POST['course_id']);
                $year_level_id = cleanNumericInput($_POST['year_level_id']);
                $section_id = cleanNumericInput($_POST['section_id']);

                if (!$student_id || !$course_id || !$year_level_id || !$section_id) {
                    $response["message"] = "Invalid numeric input.";
                } else {
                    $result = $admin->createStudent($student_id, $first_name, $middle_name, $last_name, $email, $password, $course_id, $year_level_id, $section_id);
                    $response = $result;
                }
                break;

            case 'update':
                // Sanitize inputs
                $student_id = cleanNumericInput($_POST['student_id']);
                $first_name = cleanInput($_POST['first_name']);
                $middle_name = cleanInput($_POST['middle_name']);
                $last_name = cleanInput($_POST['last_name']);
                $email = cleanInput($_POST['email']);
                $password = isset($_POST['password']) ? cleanInput($_POST['password']) : null;
                $course_id = cleanNumericInput($_POST['course_id']);
                $year_level_id = cleanNumericInput($_POST['year_level_id']);
                $section_id = cleanNumericInput($_POST['section_id']);

                if (!$student_id || !$course_id || !$year_level_id || !$section_id) {
                    $response["message"] = "Invalid numeric input.";
                } else {
                    $result = $admin->updateStudent($student_id, $first_name, $middle_name, $last_name, $email, $password, $course_id, $year_level_id, $section_id);
                    $response = $result;
                }
                break;

            case 'delete':
                // Sanitize inputs
                $student_id = cleanNumericInput($_POST['student_id']);

                if (!$student_id) {
                    $response["message"] = "Invalid student ID.";
                } else {
                    $result = $admin->deleteStudent($student_id);
                    $response = $result;
                }
                break;

            case 'fetch':
                // Fetch students
                $filters = [];
                if (isset($_POST['name'])) {
                    $filters['name'] = cleanInput($_POST['name']);
                }
                if (isset($_POST['course_id'])) {
                    $filters['course_id'] = cleanNumericInput($_POST['course_id']);
                }
                $students = $admin->getStudents($filters);
                $response["success"] = true;
                $response["data"] = $students;
                $response["message"] = "Students retrieved successfully.";
                break;
                

            default:
                $response["message"] = "Invalid action.";
        }
    } catch (Exception $e) {
        $response["message"] = "An error occurred: " . $e->getMessage();
    }
} else {
    $response["message"] = "No action specified.";
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
