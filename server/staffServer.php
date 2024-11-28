<?php
require_once '../classes/database.class.php';
require_once '../classes/_admin.class.php';
require_once '../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);
    $action = isset($_POST['action']) ? cleanInput($_POST['action']) : '';
    $admin = new Admin();

    switch ($action) {
        case 'create':
            $errors = [];
            $data = [
                'email' => cleanInput($_POST['email']),
                'password' => cleanInput($_POST['password'])
            ];

            // Validation
            if (empty($data['email'])) {
                $errors['email'] = 'Email is required.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format.';
            } elseif ($admin->staffEmailExists($data['email'])) {
                $errors['email'] = 'Email already exists.';
            }

            if (empty($data['password'])) {
                $errors['password'] = 'Password is required.';
            }

            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }

            // Hash password and create staff
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $response = $admin->createStaff($data);
            echo json_encode(['success' => $response]);
            break;

        case 'read':
            $staff = $admin->getAllStaff();
            echo json_encode($staff);
            break;

        case 'update':
            $staff_id = intval(cleanInput($_POST['staff_id']));
            $data = [
                'staff_id' => $staff_id,
                'email' => cleanInput($_POST['email']),
                'password' => cleanInput($_POST['password']) // Optional
            ];

            // Check if email already exists for another staff
            if ($admin->staffEmailExists($data['email'], $staff_id)) {
                echo json_encode(['success' => false, 'errors' => ['email' => 'This email is already taken by another staff.']]);
                exit;
            }

            // Hash new password if provided
            if (!empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            } else {
                unset($data['password']); // Keep existing password
            }

            $response = $admin->updateStaff($data);
            echo json_encode(['success' => $response]);
            break;

        case 'delete':
            $staff_id = intval(cleanInput($_POST['staff_id']));
            $response = $admin->deleteStaff($staff_id);
            echo json_encode(['success' => $response]);
            break;

        case 'get':
            if (empty($_POST['staff_id'])) {
                echo json_encode(['error' => 'Missing staff_id']);
                break;
            }

            $staff_id = intval(cleanInput($_POST['staff_id']));
            $staffData = $admin->getStaffById($staff_id);

            if ($staffData) {
                echo json_encode($staffData);
            } else {
                echo json_encode(['error' => 'Staff not found']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
