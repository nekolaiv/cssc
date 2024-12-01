<?php
require_once '../classes/database.class.php';
require_once '../classes/_staff.class.php';
require_once '../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? cleanInput($_POST['action']) : '';
    $entries = new Entries();

    switch ($action) {
        case 'read':
            $unverifiedEntries = $entries->getAllUnverifiedEntries();
            echo json_encode($unverifiedEntries);
            break;

        case 'get':
            $entry_id = intval(cleanInput($_POST['id']));
            $entry = $entries->getEntryById($entry_id);

            if ($entry) {
                echo json_encode($entry);
            } else {
                echo json_encode(['error' => 'Entry not found']);
            }
            break;

        case 'verify':
            $entry_id = intval(cleanInput($_POST['id']));
            $response = $entries->verifyEntry($entry_id);

            if ($response) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to verify the entry for ' . $entry_id]);
            }
            break;

        case 'reject':
            $entry_id = intval(cleanInput($_POST['id']));
            // Add logic for rejecting the entry here.
            // For example, you could delete or mark the entry as rejected in a different table.
            // This placeholder simply deletes the entry from the unverified table.

            $response = $entries->removeVerifiedEntry($entry_id);
            if ($response) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to reject the entry.']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
