<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "leaderboard - cs";
include_once "../../includes/_student-head.php";

?>

<body class="home-body">
    <main class="wrapper">
        <?php include_once "../../includes/_student-header.php"?>
        <div class="content">
            
        </div>
    </main>

<script>
    let session_length = <?= count($_SESSION["course-fields"]["subject_code"] ?? []) ?>
</script>
<!-- <script src="/csrs/js/student_ajax.js"></script> -->
<script src="/cssc/controllers/student-controller.js"></script>

<?php include_once "../../includes/_student-footer.php"?>



