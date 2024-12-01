<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "calculate";
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');
?>


<body class="home-body">
    <main class="wrapper">
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-header.php');?>
        <div class="content">
            <!-- content dynamic load -->
        </div>
    </main>

<!-- <script src="/csrs/js/student_ajax.js"></script> -->
<script src="/cssc/controllers/student-controller.js"></script>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-footer.php');?>



