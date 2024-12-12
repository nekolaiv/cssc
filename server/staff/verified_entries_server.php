<?php
require_once '../../classes/_staff.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? cleanInput($_POST['action']) : '';
    $entries = new Staff();

    try {
        switch ($action) {
            case 'read':
                // Fetch all verified entries
                echo json_encode($entries->getAllVerifiedEntries());
                break;

            case 'get':
                // Fetch a specific verified entry by ID
                if (empty($_POST['id'])) {
                    echo json_encode(['success' => false, 'error' => 'Missing entry ID.']);
                    break;
                }

                $entry_id = intval(cleanInput($_POST['id']));
                echo json_encode($entries->getVerifiedEntryWithDetails($entry_id));
                break;

            case 'remove':
                // Remove an entry from verified entries
                if (empty($_POST['id'])) {
                    echo json_encode(['success' => false, 'error' => 'Missing entry ID.']);
                    break;
                }

                $entry_id = intval(cleanInput($_POST['id']));
                echo json_encode($entries->removeAndLogVerifiedEntry($entry_id));
                break;

            case 'get_subject_fields':
                // Fetch subject fields for the student
                if (empty($_POST['student_id'])) {
                    echo json_encode(['success' => false, 'error' => 'Missing student ID.']);
                    break;
                }

                $student_id = intval(cleanInput($_POST['student_id']));
                echo json_encode($entries->getSubjectFieldsByStudent($student_id));
                break;

            default:
                echo json_encode(['success' => false, 'error' => 'Invalid action.']);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
