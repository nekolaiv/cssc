<?php
require_once '../classes/database.class.php';
require_once '../classes/_admin.class.php';
require_once '../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);
    $action = isset($_POST['action']) ? cleanInput($_POST['action']) : '';
    $student = new Admin();

    switch ($action) {
        case 'create':
            $errors = [];
            $data = [
                'student_id' => cleanInput($_POST['student_id']),
                'email' => cleanInput($_POST['email']),
                'password' => cleanInput($_POST['password']),
                'first_name' => cleanInput($_POST['first_name']),
                'middle_name' => cleanInput($_POST['middle_name'] ?? ''),
                'last_name' => cleanInput($_POST['last_name']),
                'course' => cleanInput($_POST['course']),
                'year_level' => intval(cleanInput($_POST['year_level'])),
                'section' => cleanInput($_POST['section']),
            ];
        
            // Validation for each field
            if (empty($data['student_id'])) {
                $errors['student_id'] = 'Student ID is required.';
            } elseif (!ctype_digit($data['student_id'])) {
                $errors['student_id'] = 'Student ID must be numeric.';
            } elseif ($student->studentIdExists($data['student_id'])) {
                $errors['student_id'] = 'Student ID already exists.';
            }
        
            if (empty($data['first_name'])) {
                $errors['first_name'] = 'First name is required.';
            }
        
            if (empty($data['last_name'])) {
                $errors['last_name'] = 'Last name is required.';
            }
        
            if (empty($data['email'])) {
                $errors['email'] = 'Email is required.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format.';
            }
        
            if (empty($data['password'])) {
                $errors['password'] = 'Password is required.';
            }
        
            if (empty($data['course'])) {
                $errors['course'] = 'Course is required.';
            }
        
            if (empty($data['year_level'])) {
                $errors['year_level'] = 'Year level is required.';
            } elseif (!ctype_digit((string)$data['year_level'])) {
                $errors['year_level'] = 'Year level must be numeric.';
            }
        
            if (empty($data['section'])) {
                $errors['section'] = 'Section is required.';
            }
        
            // Return errors if any
            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }
        
            // Proceed with creating the student
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $response = $student->createStudent($data);
            echo json_encode(['success' => $response]);
            break;

        case 'read':
            $students = $student->getAllStudents();
            echo json_encode($students);
            break;

        case 'update':
            $data = [
                'user_id' => intval(cleanInput($_POST['user_id'])),
                'email' => cleanInput($_POST['email']),
                'first_name' => cleanInput($_POST['first_name']),
                'last_name' => cleanInput($_POST['last_name']),
                'middle_name' => cleanInput($_POST['middle_name'] ?? ''),
                'course' => cleanInput($_POST['course']),
                'year_level' => intval(cleanInput($_POST['year_level'])),
                'section' => cleanInput($_POST['section'])
            ];
            $response = $student->updateStudent($data);
            echo json_encode(['success' => $response]);
            break;

        case 'delete':
            $user_id = intval(cleanInput($_POST['user_id']));
            $response = $student->deleteStudent($user_id);
            echo json_encode(['success' => $response]);
            break;
            
            case 'get':
            if (empty($_POST['user_id'])) {
                echo json_encode(['error' => 'Missing user_id']);
                break;
            }
        
            $user_id = intval(cleanInput($_POST['user_id']));
            $studentData = $student->getStudentById($user_id); // Ensure this function exists and is correct
        
            if ($studentData) {
                echo json_encode($studentData);
            } else {
                echo json_encode(['error' => 'Student not found']);
            }
            break;
            

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
