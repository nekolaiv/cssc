<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');
$student = new Student();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year_level = $_POST['year_level'] ?? '';

    if (empty($year_level)) {
        echo json_encode([
            "status" => "error",
            "message" => "No year level selected."
        ]);
        exit;
    }
    $_SESSION['cs_top1'] = $student->getStudentTopNotcher($year_level, 1);
    $_SESSION['it_top1'] = $student->getStudentTopNotcher($year_level, 2);
    $_SESSION['act_top1'] = $student->getStudentTopNotcher($year_level, 3);
    $_SESSION['cs_leaderboard'] = $student->getStudentLeaderboardData($year_level, 1);
    $_SESSION['it_leaderboard'] = $student->getStudentLeaderboardData($year_level, 2);
    $_SESSION['act_leaderboard'] = $student->getStudentLeaderboardData($year_level, 3);


    header('Content-Type: application/json');
    echo json_encode(["success" => "success loading leaderboard"]);
    
}
?>