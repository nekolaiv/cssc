<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? cleanInput($_POST['action']) : '';
    $admin = new Admin();

    switch ($action) {
        case 'create':
            $errors = [];
            $data = [
                'email' => cleanInput($_POST['email']),
                'password' => cleanInput($_POST['password']),
                'first_name' => cleanInput($_POST['first_name']),
                'last_name' => cleanInput($_POST['last_name']),
                'middle_name' => cleanInput($_POST['middle_name']),
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

            // Hash the password
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
                'password' => cleanInput($_POST['password']),
                'first_name' => cleanInput($_POST['first_name']),
                'last_name' => cleanInput($_POST['last_name']),
                'middle_name' => cleanInput($_POST['middle_name']),
            ];

            $errors = [];
            if (empty($data['email'])) {
                $errors['email'] = 'Email is required.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format.';
            } elseif ($admin->staffEmailExists($data['email'], $staff_id)) {
                $errors['email'] = 'This email is already taken by another staff.';
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

            if (!empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            } else {
                unset($data['password']);
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
            $staff_id = intval(cleanInput($_POST['staff_id']));
            $staff = $admin->getStaffById($staff_id);
            echo json_encode($staff ? $staff : ['error' => 'Staff not found']);
            break;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
