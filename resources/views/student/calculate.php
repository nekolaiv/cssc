<?php
function getSubjectFieldsHTML() {
    ob_start(); // Start output buffering
    foreach ($_SESSION['subjects'] as $index => $subject): ?>
        <div class="subjectField">
            <input type="text" name="subjects[<?= $index ?>][subject-code]" placeholder="Subject Code" value="<?php echo htmlspecialchars($subject['subject-code']); ?>" />
            <input type="text" name="subjects[<?= $index ?>][unit]" placeholder="Unit" value="<?php echo htmlspecialchars($subject['unit']); ?>" />
            <input type="text" name="subjects[<?= $index ?>][grade]" placeholder="Grade" value="<?php echo htmlspecialchars($subject['grade']); ?>" />
            <button type="submit" name="remove-subject" value="<?= $index ?>">Remove</button>
        </div>
    <?php endforeach;
    return ob_get_clean();
}
?>

<section class="calculate-section">
    <div class="div-pad" id="calculate-div1">
        <header>
            <h1>Grade Calculator</h1>
        </header>
    </div>
    <div class="div-pad" id="calculate-div2">
        <form action="" method="POST" id="subjectFieldsContainer">
            <?php echo getSubjectFieldsHTML(); // Render existing fields ?>
            <button type="submit" name="action" value="add-subject" id="add-subject">Add Subject</button>
            <button type="submit" name="action" value="save-courses" id="save-courses">Save Courses</button>
        </form>
    </div>
</section>