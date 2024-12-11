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
                $query = "SELECT status FROM student_accounts WHERE student_id = :student_id";
                $db = new Database();
                $status = $db->fetchOne($query, [':student_id' => $entry['student_id']]);

                // If there's an image, encode it properly
                if (!empty($entry['image_proof'])) {
                    $entry['image_proof'] = base64_encode($entry['image_proof']);
                }

                $entry['status'] = $status['status'] ?? 'Not Submitted';
                echo json_encode($entry);
            } else {
                echo json_encode(['error' => 'Entry not found.']);
            }
            break;

            case 'verify':
                $entry_id = intval(cleanInput($_POST['id']));
            
                // Fetch the student ID before moving the entry
                $entryDetails = $entries->getEntryById($entry_id);
                if (!$entryDetails) {
                    echo json_encode(['success' => false, 'error' => 'Entry not found or invalid.']);
                    exit;
                }
            
                $studentId = $entryDetails['student_id'] ?? 'Unknown';
            
                // Perform the verification (move to verified entries table)
                $response = $entries->verifyEntry($entry_id);
            
                if ($response) {
                    // Log the audit event after successful verification
                    $entries->logAudit(
                        'Verify Entry',
                        "Verified entry for Student ID: $studentId"
                    );
            
                    echo json_encode(['success' => true, 'message' => 'Entry verified successfully.']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to verify the entry.']);
                }
                break;
            

        case 'reject':
            $entry_id = intval(cleanInput($_POST['id']));
            $response = $entries->rejectEntry($entry_id);

            if ($response) {
                $entryDetails = $entries->getEntryById($entry_id);
                $studentId = $entryDetails['student_id'] ?? 'Unknown';
                $entries->logAudit(
                    'Reject Entry',
                    "Rejected entry for Student ID: $studentId"
                );

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
