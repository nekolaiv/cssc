<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $admin = new Admin();

    switch ($action) {
        /**
         * CREATE User and Account
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
                'curriculum_id' => cleanInput($_POST['curriculum_id'] ?? ''),
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
            if (empty($data['curriculum_id'])) $errors['curriculum_id'] = 'Curriculum is required.';

            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }

            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

            // Call Admin function to create user and account
            $response = $admin->createUser($data);

            if ($response === true) {
                $admin->logAudit('Create User', "Created user and account with Identifier: {$data['identifier']}");
                echo json_encode(['success' => true]);
            } else {
                // Return the detailed error
                echo json_encode(['success' => false, 'error' => $response]);
            }
            break;

        /**
         * Other Actions: UPDATE, READ, DELETE
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
                'curriculum_id' => cleanInput($_POST['curriculum_id'] ?? ''),
                'status' => cleanInput($_POST['status'] ?? 'active')
            ];
        
            // Validation
            if (empty($data['identifier']) || !ctype_digit($data['identifier'])) {
                $errors['identifier'] = 'Identifier is required and must be numeric.';
            }
        
            if (empty($data['username'])) {
                $errors['username'] = 'Username is required.';
            }
        
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Valid email is required.';
            }
        
            if (empty($data['first_name'])) {
                $errors['first_name'] = 'First name is required.';
            }
        
            if (empty($data['last_name'])) {
                $errors['last_name'] = 'Last name is required.';
            }
        
            if (empty($data['curriculum_id'])) {
                $errors['curriculum_id'] = 'Curriculum is required.';
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
        
            $response = $admin->updateUser($data);
        
            if ($response) {
                $admin->logAudit('Update User', "Updated user with ID: {$data['id']}");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update user.']);
            }
            break;
        

        /**
         * READ Users (with filters)
         */
        case 'read':
            $filters = [
                'search' => cleanInput($_POST['search'] ?? ''),
                'curriculum_id' => cleanInput($_POST['curriculum_id'] ?? ''),
                'status' => cleanInput($_POST['status'] ?? '')
            ];

            $users = $admin->getAllUsers($filters);
            echo json_encode($users);
            break;

        /**
         * DELETE User (Soft Delete)
         */
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

            case 'delete_permanent':
                $id = intval(cleanInput($_POST['id'] ?? 0));
            
                if ($admin->deleteUser($id)) {
                    $admin->logAudit('Permanent Delete', "Permanently deleted user ID: {$id}");
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to permanently delete user.']);
                }
                break;
                        

        /**
         * GET User by ID
         */
        case 'get':
            $id = intval(cleanInput($_POST['id'] ?? 0));
            $user = $admin->getUserById($id);

            if ($user) echo json_encode($user);
            else echo json_encode(['error' => 'User not found.']);
            break;

        /**
         * FETCH All Curriculums
         */
        case 'fetch_curriculums':
            $curriculums = $admin->getCurriculums();
            echo json_encode($curriculums);
            break;

        /**
         * FETCH All Status Options
         */
        case 'fetch_status_options':
            $statuses = ['active', 'inactive'];
            echo json_encode($statuses);
            break;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
