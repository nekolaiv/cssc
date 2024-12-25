<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_staff.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $staff = new Staff();

    switch ($action) {
        /**
         * Fetch All Audit Logs
         */
        case 'read':
            $filters = [
                'role_id' => cleanInput($_POST['role_id'] ?? ''),
            ];

            try {
                $logs = $staff->getAllAuditLogs($filters);

                if (!$logs) {
                    echo json_encode(['success' => false, 'error' => 'No logs found.']);
                } else {
                    echo json_encode(['success' => true, 'data' => $logs]);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Failed to fetch audit logs.']);
            }
            break;

        /**
         * Fetch All Roles
         */
        case 'fetch_roles':
            try {
                $roles = $staff->getAllRoles();
                echo json_encode(['success' => true, 'data' => $roles]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Failed to fetch roles.']);
            }
            break;

        /**
         * Delete Audit Log
         */
        case 'delete':
            $id = intval(cleanInput($_POST['id'] ?? 0));

            if (empty($id)) {
                echo json_encode(['success' => false, 'error' => 'Invalid log ID.']);
                exit;
            }

            try {
                $response = $staff->deleteAuditLog($id);

                if ($response) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to delete log.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'An error occurred while deleting the log.']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action.']);
            break;
    }
}
