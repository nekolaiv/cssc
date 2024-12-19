<?php
require_once '../../classes/_admin.class.php';

header('Content-Type: application/json; charset=utf-8'); // Ensure JSON response
$admin = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'getCounts':
                $counts = $admin->getAccountCounts();
                echo json_encode([
                    'success' => true, 
                    'students' => $counts['students'], 
                    'staff' => $counts['staff'], 
                    'admins' => $counts['admins']
                ]);
                break;

            case 'getAdvisers':
                $advisers = $admin->getAdvisers();
                echo json_encode(['success' => true, 'advisers' => $advisers]);
                break;

            case 'getAuditLogs':
                $logs = $admin->getAuditLogs();
                echo json_encode(['success' => true, 'logs' => $logs]);
                break;

            default:
                echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit; // Ensure no additional output
}
?>
