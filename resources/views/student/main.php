<?php include_once "../resources/views/student/includes/header.php"?>

<main id="content">
    <!-- Content will be loaded here -->
</main>
<script>
    let session_length = <?= count($_SESSION["course-fields"]["subject_code"] ?? []) ?>
</script>
<script src="/cssc/resources/js/student-AJAX.js"></script>



