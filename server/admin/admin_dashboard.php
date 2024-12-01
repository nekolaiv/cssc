<?php
require_once '../../classes/database.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $db = new Database();

    if ($action === 'getCounts') {
        try {
            $students = $db->fetchOne("SELECT COUNT(*) as count FROM registered_students")['count'] ?? 0;
            $staff = $db->fetchOne("SELECT COUNT(*) as count FROM staff_accounts")['count'] ?? 0;
            $admins = $db->fetchOne("SELECT COUNT(*) as count FROM admin_accounts")['count'] ?? 0;

            echo json_encode(['success' => true, 'students' => $students, 'staff' => $staff, 'admins' => $admins]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } elseif ($action === 'getAdvisers') {
        try {
            $advisers = $db->fetchAll("
                SELECT
                    CONCAT(first_name, ' ', last_name) as name,
                    email,
                    course,
                    year_level
                FROM advisers
            ");

            echo json_encode(['success' => true, 'advisers' => $advisers]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid action.']);
    }
}
?>
