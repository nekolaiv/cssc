<?php
require_once '../../classes/database.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $db = new Database();

    switch ($action) {
        case 'getCounts':
            // Fetch counts for the overview cards
            $data = [
                'unverifiedCount' => $db->fetchColumn("SELECT COUNT(*) FROM students_unverified_entries"),
                'verifiedCount' => $db->fetchColumn("SELECT COUNT(*) FROM students_verified_entries"),
                'pendingCount' => $db->fetchColumn("SELECT COUNT(*) FROM registered_students WHERE status = 'Pending'"),
                'revisionCount' => $db->fetchColumn("SELECT COUNT(*) FROM registered_students WHERE status = 'Need Revision'")
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

        case 'recently_verified':
            $query = "SELECT student_id, fullname, course, gwa, updated_at AS date_verified 
                        FROM students_verified_entries 
                        ORDER BY updated_at DESC LIMIT 5";
            $entries = $db->fetchAll($query);
        
            // Add error handling
            if (!$entries) {
                echo json_encode(['success' => false, 'message' => 'No recent verified entries found.']);
            } else {
                echo json_encode(['success' => true, 'entries' => $entries]);
            }
            break;
            
        case 'auditLog':
            // Fetch recent audit log actions
            // $query = "SELECT action_date, action, details FROM staff_audit_log ORDER BY action_date DESC LIMIT 5";
            // $log = $db->fetchAll($query);
            // echo json_encode(['success' => true, 'log' => $log]);
            // break;

        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
            break;
    }
}
