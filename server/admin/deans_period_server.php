<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $admin = new Admin();

    switch ($action) {
        /**
         * Fetch All Periods
         */
        case 'read':
            $filters = [
                'search' => cleanInput($_POST['search'] ?? ''),
                'status' => cleanInput($_POST['status'] ?? '')
            ];

            try {
                $periods = $admin->getAllPeriods($filters);
                echo json_encode($periods);
            } catch (Exception $e) {
                echo json_encode(['error' => 'Failed to fetch periods.']);
            }
            break;

        /**
         * Create or Update Period
         */
        case 'save':
            $id = intval(cleanInput($_POST['id'] ?? 0));
            $data = [
                'year' => cleanInput($_POST['year'] ?? ''),
                'semester' => cleanInput($_POST['semester'] ?? ''),
                'start_date' => cleanInput($_POST['start_date'] ?? ''),
                'end_date' => cleanInput($_POST['end_date'] ?? ''),
                'status' => cleanInput($_POST['status'] ?? '')
            ];

            try {
                if ($id > 0) {
                    $result = $admin->updatePeriod($id, $data);
                } else {
                    $result = $admin->createPeriod($data);
                }

                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['error' => 'Failed to save the period.']);
                }
            } catch (Exception $e) {
                echo json_encode(['error' => 'An error occurred while saving the period.']);
            }
            break;

        /**
         * Toggle Period Status
         */
        case 'toggle_status':
            $id = intval(cleanInput($_POST['id'] ?? 0));

            try {
                $result = $admin->togglePeriodStatus($id);

                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['error' => 'Failed to toggle the period status.']);
                }
            } catch (Exception $e) {
                echo json_encode(['error' => 'An error occurred while toggling the period status.']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
