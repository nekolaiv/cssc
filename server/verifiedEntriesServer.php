<?php
require_once '../classes/database.class.php';
require_once '../classes/_staff.class.php';
require_once '../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? cleanInput($_POST['action']) : '';
    $entries = new Staff();

    try {
        switch ($action) {
            case 'read':
                // Fetch all verified entries
                $verifiedEntries = $entries->getAllVerifiedEntries();
                echo json_encode($verifiedEntries);
                break;

            case 'get':
                // Fetch a specific verified entry by ID
                if (empty($_POST['id'])) {
                    echo json_encode(['success' => false, 'error' => 'Missing entry ID.']);
                    break;
                }

                $entry_id = intval(cleanInput($_POST['id']));
                $entry = $entries->getVerifiedEntryById($entry_id);

                if ($entry) {
                    echo json_encode(['success' => true, 'entry' => $entry]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Entry not found.']);
                }
                break;

            case 'remove':
                // Remove an entry from verified entries
                if (empty($_POST['id'])) {
                    echo json_encode(['success' => false, 'error' => 'Missing entry ID.']);
                    break;
                }

                $entry_id = intval(cleanInput($_POST['id']));
                $response = $entries->removeVerifiedEntry($entry_id);

                if ($response) {
                    echo json_encode(['success' => true, 'message' => 'Entry removed successfully.']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to remove the entry.']);
                }
                break;

            default:
                echo json_encode(['success' => false, 'error' => 'Invalid action.']);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
