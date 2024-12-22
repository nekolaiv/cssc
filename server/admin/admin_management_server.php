<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $admin = new Admin();

    switch ($action) {
        /**
         * CREATE Admin Account
         */
        case 'create':
            $errors = [];
            $data = [
                'identifier' => cleanInput($_POST['identifier'] ?? ''),
                'username' => cleanInput($_POST['username'] ?? ''),
                'email' => cleanInput($_POST['email'] ?? ''),
                'password' => cleanInput($_POST['password'] ?? ''),
                'first_name' => cleanInput($_POST['first_name'] ?? ''),
                'middle_name' => cleanInput($_POST['middle_name'] ?? ''),
                'last_name' => cleanInput($_POST['last_name'] ?? ''),
                'role_id' => 3, // Role ID for Admin
                'status' => cleanInput($_POST['status'] ?? 'active')
            ];

            // Validation
            if (empty($data['identifier']) || !ctype_digit($data['identifier'])) {
                $errors['identifier'] = 'Identifier is required and must be numeric.';
            } elseif ($admin->identifierExists($data['identifier'])) {
                $errors['identifier'] = 'Identifier already exists.';
            }

            if (empty($data['username'])) {
                $errors['username'] = 'Username is required.';
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Valid email is required.';
            }

            if (empty($data['password'])) {
                $errors['password'] = 'Password is required.';
            }

            if (empty($data['first_name'])) $errors['first_name'] = 'First name is required.';
            if (empty($data['last_name'])) $errors['last_name'] = 'Last name is required.';

            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }

            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

            // Create Admin Account
            $response = $admin->createAdmin($data);

            if ($response === true) {
                $admin->logAudit('Create Admin', "Created admin account with Identifier: {$data['identifier']}");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $response]);
            }
            break;

        /**
         * UPDATE Admin Account
         */
        case 'update':
            $errors = [];
            $data = [
                'id' => intval(cleanInput($_POST['id'] ?? 0)),
                'identifier' => cleanInput($_POST['identifier'] ?? ''),
                'username' => cleanInput($_POST['username'] ?? ''),
                'email' => cleanInput($_POST['email'] ?? ''),
                'password' => cleanInput($_POST['password'] ?? ''), // Optional password update
                'first_name' => cleanInput($_POST['first_name'] ?? ''),
                'middle_name' => cleanInput($_POST['middle_name'] ?? ''),
                'last_name' => cleanInput($_POST['last_name'] ?? ''),
                'status' => cleanInput($_POST['status'] ?? 'active')
            ];

            // Validation
            if (empty($data['username'])) {
                $errors['username'] = 'Username is required.';
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Valid email is required.';
            }

            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }

            // Optional password hashing
            if (!empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            } else {
                unset($data['password']); // Prevent empty password overwrites
            }

            $response = $admin->updateAdmin($data);

            if ($response) {
                $admin->logAudit('Update Admin', "Updated admin with ID: {$data['id']}");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update admin.']);
            }
            break;

        /**
         * READ Admins
         */
        case 'read':
            $filters = [
                'search' => cleanInput($_POST['search'] ?? ''),
                'status' => cleanInput($_POST['status'] ?? '')
            ];

            $admins = $admin->getAllAdmins($filters); // Specific function for fetching admins
            echo json_encode($admins);
            break;

        /**
         * TOGGLE Admin Status (Activate/Deactivate)
         */
        case 'toggle_status':
            $id = intval(cleanInput($_POST['id'] ?? 0));
            $status = cleanInput($_POST['status'] ?? '');

            if ($admin->updateAccountStatus($id, $status)) {
                $admin->logAudit('Toggle Admin Status', "Admin ID {$id} status changed to {$status}");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update admin status.']);
            }
            break;

        case 'get':
            $id = intval(cleanInput($_POST['id'] ?? 0)); // Admin ID to fetch
        
            if (empty($id)) {
                echo json_encode(['success' => false, 'error' => 'Invalid Admin ID.']);
                exit;
            }
        
            // Call Admin function to get admin data by ID
            $adminData = $admin->getAdminById($id);
        
            if ($adminData) {
                echo json_encode(['success' => true, 'data' => $adminData]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Admin not found.']);
            }
            break;
        
            case 'delete':
                $id = intval(cleanInput($_POST['id'] ?? 0)); // Admin ID to delete
            
                if (empty($id)) {
                    echo json_encode(['success' => false, 'error' => 'Invalid Admin ID.']);
                    exit;
                }
            
                // Call Admin function to delete admin
                $response = $admin->deleteAdmin($id);
            
                if ($response) {
                    $admin->logAudit('Delete Admin', "Admin ID {$id} deleted successfully.");
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to delete admin.']);
                }
                break;
            
        

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
