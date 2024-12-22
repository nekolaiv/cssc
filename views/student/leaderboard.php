<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "leaderboard";
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');
$student = new Student();
$year_level = NULL;
$_SESSION['cs_top1'] = $student->getStudentTopNotcher($year_level, 1);
$_SESSION['it_top1'] = $student->getStudentTopNotcher($year_level, 2);
$_SESSION['act_top1'] = $student->getStudentTopNotcher($year_level, 3);
$_SESSION['cs_leaderboard'] = $student->getStudentLeaderboardData($year_level, 1);
$_SESSION['it_leaderboard'] = $student->getStudentLeaderboardData($year_level, 2);
$_SESSION['act_leaderboard'] = $student->getStudentLeaderboardData($year_level, 3);

// $cs_top1 = $student->getCSTopNotcher();
// $it_top1 = $student->getITTopNotcher();
// $act_top1 = $student->getACTTopNotcher();
// $cs_leaderboard = $student->getCSLeaderboardData();
// $it_leaderboard = $student->getITLeaderboardData();
// $act_leaderboard = $student->getACTLeaderboardData();



// if($_SERVER['REQUEST_METHOD'] === 'POST'){
//     if(isset($_POST['filter-year'])){
//         $year_level = $_POST['year-level'];
//         echo "<script>alert('leaderboard');</script>";
//     }
// }



?>

<body class="home-body">
    <main class="wrapper">
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-header.php');?>
        <div class="content">
            
        </div>
    </main>

<script>
    let session_length = <?= count($_SESSION["course-fields"]["subject_code"] ?? []) ?>
</script>
<!-- <script src="/csrs/js/student_ajax.js"></script> -->
<script src="/cssc/controllers/student-controller.js"></script>

<?php include_once "../../includes/_student-footer.php"?>



