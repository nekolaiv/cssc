<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$page_title = "leaderboard";
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');
?>

<body class="home-body">
    <!-- <script src="/cssc/vendor/datatable-2.1.8/datatables.min.js"></script>
    <script src="/cssc/vendor/chartjs-4.4.5/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/cssc/vendor/jquery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->
    <main class="wrapper">
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-header.php');?>
        <div class="content">
            
        </div>
    </main>

<!-- <script src="/csrs/js/student_ajax.js"></script> -->
<script src="/cssc/controllers/student-controller.js"></script>

<?php include_once "../../includes/_student-footer.php"?>



