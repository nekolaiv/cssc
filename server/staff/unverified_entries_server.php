<?php
require_once '../../classes/_staff.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? cleanInput($_POST['action']) : '';
    $entries = new Staff();

    switch ($action) {
        case 'read':
            // Fetch all unverified entries
            echo json_encode($entries->getAllUnverifiedEntries());
            break;

        case 'get':
            // Get specific entry details
            $entry_id = intval(cleanInput($_POST['id']));
            echo json_encode($entries->getEntryWithStatus($entry_id));
            break;

        case 'verify':
            // Verify the entry
            $entry_id = intval(cleanInput($_POST['id']));
            echo json_encode($entries->verifyAndLogEntry($entry_id));
            break;

        case 'reject':
            // Reject the entry
            $entry_id = intval(cleanInput($_POST['id']));
            echo json_encode($entries->rejectAndLogEntry($entry_id));
            break;

        case 'get_subject_fields':
            // Fetch subject fields for the student
            $student_id = intval(cleanInput($_POST['student_id']));
            echo json_encode($entries->getSubjectFieldsByStudent($student_id));
            break;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
?>
