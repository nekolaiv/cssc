<?php



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['filter-year'])){
        $year_level = $_POST['year-level'];
    }
} 
$year_level = NULL;$year_level = NULL;

$_SESSION['cs_top1'] = $student->getStudentTopNotcher($year_level, 'BSCS');
$_SESSION['it_top1'] = $student->getStudentTopNotcher($year_level, 'BSIT');
$_SESSION['act_top1'] = $student->getStudentTopNotcher($year_level, 'ACT');
$_SESSION['cs_leaderboard'] = $student->getStudentLeaderboardData($year_level, 'BSCS');
$_SESSION['it_leaderboard'] = $student->getStudentLeaderboardData($year_level, 'BSIT');
$_SESSION['act_leaderboard'] = $student->getStudentLeaderboardData($year_level, 'ACT');

header('Content-Type: application/json');
echo json_encode(["success" => "success loading leaderboard"]);
?>