<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $admin = new Admin();

    switch ($action) {
        /**
         * CREATE Staff and Account
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
                'department_id' => intval(cleanInput($_POST['department_id'] ?? 0)),
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
            } elseif ($admin->usernameExists($data['username'])) {
                $errors['username'] = 'Username already exists.';
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Valid email is required.';
            } elseif ($admin->emailExists($data['email'])) {
                $errors['email'] = 'Email already exists.';
            }

            if (empty($data['password'])) {
                $errors['password'] = 'Password is required.';
            }

            if (empty($data['first_name'])) $errors['first_name'] = 'First name is required.';
            if (empty($data['last_name'])) $errors['last_name'] = 'Last name is required.';
            if (empty($data['department_id']) || !$admin->departmentExists($data['department_id'])) {
                $errors['department_id'] = 'Valid department is required.';
            }

            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }

            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

            // Call Admin function to create user and account
            $response = $admin->createStaff($data);

            if ($response === true) {
                $admin->logAudit('Create Staff', "Created staff with Identifier: {$data['identifier']}");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $response]);
            }
            break;

        /**
         * READ Staff (with filters)
         */
        case 'read':
            $filters = [
                'search' => cleanInput($_POST['search'] ?? ''),
                'department_id' => cleanInput($_POST['department_id'] ?? ''),
                'status' => cleanInput($_POST['status'] ?? '')
            ];

            $staff = $admin->getAllStaff($filters);
            echo json_encode($staff);
            break;

        /**
         * UPDATE Staff
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
                'department_id' => intval(cleanInput($_POST['department_id'] ?? 0)),
                'status' => cleanInput($_POST['status'] ?? 'active')
            ];

            // Validation
            if (empty($data['id'])) {
                $errors['id'] = 'Valid staff ID is required.';
            }

            if (empty($data['identifier']) || !ctype_digit($data['identifier'])) {
                $errors['identifier'] = 'Identifier is required and must be numeric.';
            }

            if (empty($data['username'])) {
                $errors['username'] = 'Username is required.';
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Valid email is required.';
            }

            if (empty($data['first_name'])) $errors['first_name'] = 'First name is required.';
            if (empty($data['last_name'])) $errors['last_name'] = 'Last name is required.';
            if (empty($data['department_id']) || !$admin->departmentExists($data['department_id'])) {
                $errors['department_id'] = 'Valid department is required.';
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

            $response = $admin->updateStaff($data);

            if ($response) {
                $admin->logAudit('Update Staff', "Updated staff with Identifier: {$data['identifier']}");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update staff.']);
            }
            break;

        /**
         * DELETE Staff (Soft Delete)
         */
        case 'delete':
            $id = intval(cleanInput($_POST['id'] ?? 0));

            if ($admin->softDeleteStaff($id)) {
                $admin->logAudit('Delete Staff', "Soft deleted staff with ID: {$id}");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete staff.']);
            }
            break;

        case 'fetch_departments':
            $departments = $admin->getDepartments(); // Ensure `getDepartments` fetches the department data
            echo json_encode($departments);
            break;

        case 'fetch_status_options':
            $statuses = ['active', 'inactive']; // Static options
            echo json_encode($statuses);
            break;

        case 'toggle_status':
            $id = intval(cleanInput($_POST['id'] ?? 0));
            $status = cleanInput($_POST['status'] ?? '');
        
            if ($admin->updateAccountStatus($id, $status)) {
                $admin->logAudit('Toggle Account Status', "Account ID {$id} status changed to {$status}");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update account status.']);
            }
            break;
        
        case 'get':
            $id = intval(cleanInput($_POST['id'] ?? 0)); // Ensure ID is provided and valid
        
            if ($id <= 0) {
                echo json_encode(['error' => 'Invalid staff ID.']);
                exit;
            }
        
            // Fetch the staff member by ID
            $staff = $admin->getStaffById($id);
        
            if ($staff) {
                echo json_encode($staff);
            } else {
                echo json_encode(['error' => 'Staff not found.']);
            }
            break;
        
            
                
            

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
