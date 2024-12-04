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
            $email = cleanInput($_POST['email']);
            $password = cleanInput($_POST['password']);
            $first_name = cleanInput($_POST['first_name']);
            $last_name = cleanInput($_POST['last_name']);
            $middle_name = cleanInput($_POST['middle_name']);

            // Validation
            if (empty($email)) {
                $errors['email'] = 'Email is required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format.';
            } elseif ($admin->emailExists($email)) {
                $errors['email'] = 'This email is already in use.';
            }

            if (empty($password)) {
                $errors['password'] = 'Password is required.';
            }

            if (empty($first_name)) {
                $errors['first_name'] = 'First name is required.';
            }

            if (empty($last_name)) {
                $errors['last_name'] = 'Last name is required.';
            }

            if (empty($middle_name)) {
                $errors['middle_name'] = 'Middle name is required.';
            }

            // If errors, return them
            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }

            // If no errors, hash the password and proceed
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $result = $admin->createAdmin([
                'email' => $email,
                'password' => $hashedPassword,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'middle_name' => $middle_name
            ]);

            if ($result) {
                $admin->logAudit('Create Admin', "Created admin account for email: $email");
            }

            echo json_encode(['success' => $result]);
            break;

        case 'read':
            $admins = $admin->getAllAdmins();
            echo json_encode($admins);
            break;

        case 'update':
            $admin_id = intval(cleanInput($_POST['admin_id']));
            $email = cleanInput($_POST['email']);
            $password = cleanInput($_POST['password']);
            $first_name = cleanInput($_POST['first_name']);
            $last_name = cleanInput($_POST['last_name']);
            $middle_name = cleanInput($_POST['middle_name']);

            // Validation
            $errors = [];
            if (empty($email)) {
                $errors['email'] = 'Email is required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format.';
            } elseif ($admin->emailExists($email, $admin_id)) {
                $errors['email'] = 'This email is already in use by another admin.';
            }

            if (empty($first_name)) {
                $errors['first_name'] = 'First name is required.';
            }

            if (empty($last_name)) {
                $errors['last_name'] = 'Last name is required.';
            }

            if (empty($middle_name)) {
                $errors['middle_name'] = 'Middle name is required.';
            }

            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            } else {
                $hashedPassword = null; // Keep existing password if no new one is provided
            }

            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }

            // Update admin
            $data = [
                'admin_id' => $admin_id,
                'email' => $email,
                'password' => $hashedPassword,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'middle_name' => $middle_name
            ];

            $response = $admin->updateAdmin($data);

            if ($response) {
                $admin->logAudit('Update Admin', "Updated admin account for ID: $admin_id");
            }

            echo json_encode(['success' => $response]);
            break;

        case 'delete':
            $admin_id = intval(cleanInput($_POST['admin_id']));
            $response = $admin->deleteAdmin($admin_id);

            if ($response) {
                $admin->logAudit('Delete Admin', "Deleted admin account with ID: $admin_id");
            }

            echo json_encode(['success' => $response]);
            break;

        case 'get':
            if (empty($_POST['admin_id'])) {
                echo json_encode(['error' => 'Missing admin_id']);
                break;
            }

            $admin_id = intval(cleanInput($_POST['admin_id']));
            $adminData = $admin->getAdminById($admin_id);

            if ($adminData) {
                echo json_encode($adminData);
            } else {
                echo json_encode(['error' => 'Admin not found']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
?>
