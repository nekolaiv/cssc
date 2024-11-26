<?php
require_once '../classes/database.class.php';
require_once '../classes/student.class.php';
require_once '../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? cleanInput($_POST['action']) : '';

    $student = new Student();

    switch ($action) {
        case 'create':
            $data = [
                'student_id' => cleanInput($_POST['student_id']),
                'email' => cleanInput($_POST['email']),
                'password' => password_hash(cleanInput($_POST['password']), PASSWORD_BCRYPT),
                'first_name' => cleanInput($_POST['first_name']),
                'last_name' => cleanInput($_POST['last_name']),
                'middle_name' => cleanInput($_POST['middle_name']),
                'course' => cleanInput($_POST['course']),
                'year_level' => intval(cleanInput($_POST['year_level'])),
                'section' => cleanInput($_POST['section'])
            ];
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
                'middle_name' => cleanInput($_POST['middle_name']),
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

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
