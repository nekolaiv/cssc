<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$page_title = "leaderboard";
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');
?>

<body class="home-body">
    <main class="wrapper">
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-header.php');?>
        <div class="content">
            
        </div>
    </main>

<!-- <script src="/csrs/js/student_ajax.js"></script> -->
<script src="/cssc/controllers/student-controller.js"></script>

<?php include_once "../../includes/_student-footer.php"?>



