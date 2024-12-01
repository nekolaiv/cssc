<?php
require_once '../../classes/database.class.php'; // Include the Database class

// Initialize the Database class
$db = new Database();

try {
    // Queries to get counts for students, staff, and admin
    $queryStudents = "SELECT COUNT(*) AS student_count FROM registered_students";
    $queryStaff = "SELECT COUNT(*) AS staff_count FROM staff_accounts";
    $queryAdmin = "SELECT COUNT(*) AS admin_count FROM admin_accounts";

    // Fetch counts using the fetchOne method
    $studentsCount = $db->fetchOne($queryStudents)['student_count'];
    $staffCount = $db->fetchOne($queryStaff)['staff_count'];
    $adminCount = $db->fetchOne($queryAdmin)['admin_count'];

    // Return the counts as JSON for frontend use
    echo json_encode([
        'students' => $studentsCount,
        'staff' => $staffCount,
        'admins' => $adminCount,
    ]);
} catch (Exception $e) {
    // Handle any errors
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>