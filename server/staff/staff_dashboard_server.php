<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_staff.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $staff = new Staff();

    switch ($action) {
        case 'fetch_statistics':
            try {
                $statistics = [
                    'pending' => $staff->getApplicationsCountByStatus('Pending'),
                    'approved' => $staff->getApplicationsCountByStatus('Approved'),
                    'rejected' => $staff->getApplicationsCountByStatus('Rejected')
                ];
                echo json_encode(['success' => true, 'data' => $statistics]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;

        case 'fetch_recent_verified':
            try {
                $recentVerified = $staff->getRecentlyVerifiedApplications();
                echo json_encode(['success' => true, 'data' => $recentVerified]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;

        case 'fetch_audit_logs':
            try {
                $auditLogs = $staff->getAuditLogs();
                echo json_encode(['success' => true, 'data' => $auditLogs]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action.']);
            break;
    }
}
