<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
$student = new Student();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year_level = NULL;
    $submission_id = NULL;
    if($_POST['year_level'] != 'all'){
        $year_level = $_POST['year_level'];
    }

    if($_POST['submission_period'] != 'all'){
        $submission_id = $_POST['submission_period'];
    }

    $_SESSION['year_level'] = $year_level;
    $_SESSION['submission_id'] = $submission_id;
    $_SESSION['cs_top1'] = $student->getStudentTopNotcher($year_level, 1, $submission_id);
    $_SESSION['it_top1'] = $student->getStudentTopNotcher($year_level, 2, $submission_id);
    $_SESSION['act_top1'] = $student->getStudentTopNotcher($year_level, 3, $submission_id);
    $_SESSION['cs_leaderboard'] = $student->getStudentLeaderboardData($year_level, 1, $submission_id);
    $_SESSION['it_leaderboard'] = $student->getStudentLeaderboardData($year_level, 2, $submission_id);
    $_SESSION['act_leaderboard'] = $student->getStudentLeaderboardData($year_level, 3, $submission_id);
}
header('Content-Type: application/json');
echo json_encode(["success" => "success loading leaderboard"]);
?>