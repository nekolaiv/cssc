<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php'; // Include the Admin class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $db = new Database(); // Existing database object
    $admin = new Admin(); // New Admin object

    if ($action === 'getCounts') {
        try {
            $students = $db->fetchOne("SELECT COUNT(*) as count FROM registered_students")['count'] ?? 0;
            $staff = $db->fetchOne("SELECT COUNT(*) as count FROM staff_accounts")['count'] ?? 0;
            $admins = $db->fetchOne("SELECT COUNT(*) as count FROM admin_accounts")['count'] ?? 0;

            echo json_encode(['success' => true, 'students' => $students, 'staff' => $staff, 'admins' => $admins]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } elseif ($action === 'getAdvisers') {
        try {
            $advisers = $db->fetchAll("
                SELECT
                    CONCAT(first_name, ' ', last_name) as name,
                    email,
                    course,
                    year_level
                FROM advisers
            ");

            echo json_encode(['success' => true, 'advisers' => $advisers]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } elseif ($action === 'getAuditLogs') { // New audit logs action
        try {
            $logs = $admin->fetchAuditLogs(); // Fetch logs from Admin class

            // Combine role and name into the `role` column
            foreach ($logs as &$log) {
                $log['role'] = strtoupper($log['role']) . " - " . ucfirst($log['name']);
                unset($log['name']); // Remove the separate `name` column
            }

            echo json_encode(['success' => true, 'logs' => $logs]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid action.']);
    }
}
?>
