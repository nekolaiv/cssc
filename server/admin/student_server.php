<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

            // Validation
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

            // Create student
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $response = $student->createStudent($data);

            if ($response) {
                $student->logAudit('Create Student', "Created student account with ID: {$data['student_id']}");
            }

            echo json_encode(['success' => $response]);
            break;

        case 'read':
            $students = $student->getAllStudents();
            echo json_encode($students);
            break;

        case 'update':
            $user_id = intval(cleanInput($_POST['user_id']));
            $data = [
                'user_id' => $user_id,
                'student_id' => cleanInput($_POST['student_id']),
                'email' => cleanInput($_POST['email']),
                'password' => cleanInput($_POST['password']), // Optional
                'first_name' => cleanInput($_POST['first_name']),
                'middle_name' => cleanInput($_POST['middle_name'] ?? ''),
                'last_name' => cleanInput($_POST['last_name']),
                'course' => cleanInput($_POST['course']),
                'year_level' => intval(cleanInput($_POST['year_level'])),
                'section' => cleanInput($_POST['section']),
            ];

            // Validation
            $errors = [];
            if (empty($data['student_id'])) {
                $errors['student_id'] = 'Student ID is required.';
            } elseif (!ctype_digit($data['student_id'])) {
                $errors['student_id'] = 'Student ID must be numeric.';
            } elseif ($student->studentIdExists($data['student_id'], $user_id)) {
                $errors['student_id'] = 'This Student ID is already taken.';
            }

            if (empty($data['first_name'])) {
                $errors['first_name'] = 'First name is required.';
            }

            if (empty($data['last_name'])) {
                $errors['last_name'] = 'Last name is required.';
            }

            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }

            // Hash password if provided
            if (!empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            } else {
                unset($data['password']); // Keep existing password
            }

            $response = $student->updateStudent($data);

            if ($response) {
                $student->logAudit('Update Student', "Updated student account with ID: {$data['student_id']}");
            }

            echo json_encode(['success' => $response]);
            break;

        case 'delete':
            $user_id = intval(cleanInput($_POST['user_id']));
            $studentData = $student->getStudentById($user_id);
            $response = $student->deleteStudent($user_id);

            if ($response) {
                $student->logAudit('Delete Student', "Deleted student account with ID: {$studentData['student_id']}");
            }

            echo json_encode(['success' => $response]);
            break;

        case 'get':
            if (empty($_POST['user_id'])) {
                echo json_encode(['error' => 'Missing user_id']);
                break;
            }

            $user_id = intval(cleanInput($_POST['user_id']));
            $studentData = $student->getStudentById($user_id);

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
