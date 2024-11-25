<?php include_once "../../includes/_student-header.php"?>

<main id="content">
    <!-- Content will be loaded here -->
</main>

<script>
    let session_length = <?= count($_SESSION["course-fields"]["subject_code"] ?? []) ?>
</script>
<!-- <script src="/csrs/js/student_ajax.js"></script> -->
<script src="/csrs/controllers/student-controller.js"></script>



