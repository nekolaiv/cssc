<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_staff.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? cleanInput($_POST['action']) : '';
    $entries = new Staff();

    switch ($action) {
        case 'read':
            $unverifiedEntries = $entries->getAllUnverifiedEntries();
            echo json_encode($unverifiedEntries);
            break;

        case 'get':
            $entry_id = intval(cleanInput($_POST['id']));
            $entry = $entries->getEntryById($entry_id);

            if ($entry) {
                $query = "SELECT status FROM registered_students WHERE student_id = :student_id";
                $db = new Database();
                $status = $db->fetchOne($query, [':student_id' => $entry['student_id']]);

                $entry['status'] = $status['status'] ?? 'Not Submitted';
                echo json_encode($entry);
            } else {
                echo json_encode(['error' => 'Entry not found.']);
            }
            break;

        case 'verify':
            $entry_id = intval(cleanInput($_POST['id']));
            $response = $entries->verifyEntry($entry_id);

            if ($response) {
                echo json_encode(['success' => true, 'message' => 'Entry verified successfully.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to verify the entry.']);
            }
            break;

        case 'reject':
            $entry_id = intval(cleanInput($_POST['id']));
            $response = $entries->rejectEntry($entry_id);

            if ($response) {
                echo json_encode(['success' => true, 'message' => 'Entry rejected successfully.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to reject the entry.']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
?>
