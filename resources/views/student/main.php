<?php include_once "../resources/views/student/includes/header.php" ?>

<main id="content">
    <!-- Content will be loaded here -->
</main>
<script src="/cssc/resources/js/student-AJAX.js"></script>
<script>
    function saveToSession() {
        const subjects = localStorage.getItem('subjects');
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_subjects.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Subjects saved to session');
            } else {
                console.error('Error saving subjects:', xhr.statusText);
            }
        };
        xhr.send(subjects);
    }

    // Call saveToSession() when needed, e.g., on a form submission
</script>


