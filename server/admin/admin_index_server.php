<?php
require_once '../../classes/_admin.class.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        $admin = new Admin();

        switch ($action) {
            case 'get_dashboard_data':
                // Fetch current academic term
                $currentTerm = $admin->getCurrentAcademicTerm();
                $data = [
                    'academic_year' => $currentTerm['academic_year'] ?? 'N/A',
                    'semester' => $currentTerm['semester'] ?? 'N/A',
                ];
                echo json_encode(['success' => true, 'data' => $data]);
                break;

            default:
                throw new Exception('Invalid action.');
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
