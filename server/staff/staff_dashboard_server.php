<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php'; // Include the Admin class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $db = new Database();
    $admin = new Admin(); // New Admin object for fetching audit logs

    switch ($action) {
        case 'getCounts':
            // Fetch counts for the overview cards
            $data = [
                'unverifiedCount' => $db->fetchColumn("SELECT COUNT(*) FROM students_unverified_entries"),
                'verifiedCount' => $db->fetchColumn("SELECT COUNT(*) FROM students_verified_entries"),
                'pendingCount' => $db->fetchColumn("SELECT COUNT(*) FROM student_accounts WHERE status = 'Pending'"),
                'revisionCount' => $db->fetchColumn("SELECT COUNT(*) FROM student_accounts WHERE status = 'Need Revision'")
            ];
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        case 'recentVerified':
            // Fetch recently verified entries
            $query = "SELECT student_id, fullname, course, gwa, updated_at AS date_verified 
                      FROM students_verified_entries ORDER BY updated_at DESC LIMIT 5";
            $entries = $db->fetchAll($query);
            echo json_encode(['success' => true, 'entries' => $entries]);
            break;

        case 'auditLog':
            try {
                $logs = $admin->fetchAuditLogs(); // Fetch audit logs from Admin class

                // Combine role and name into the `role` column for display
                foreach ($logs as &$log) {
                    $log['role'] = strtoupper($log['role']) . " - " . ucfirst($log['name']);
                    unset($log['name']); // Remove the separate `name` column
                }

                echo json_encode(['success' => true, 'logs' => $logs]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
            break;
    }
}
