<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "calculate";

require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
$student = new Student();

$subject_load = $student->loadStudentsSubjects($_SESSION['profile']['email']);

for($i = 0; $i < count($subject_load); $i++){
    $_SESSION['course-fields']['subject-name'][$i] = $subject_load[$i]['subject_name'];
    $_SESSION['course-fields']['subject-code'][$i] = $subject_load[$i]['subject_code'];
    $_SESSION['course-fields']['units'][$i] = $subject_load[$i]['units'];
}


?>
<script>
    let session_length = <?= count($_SESSION["course-fields"]["subject_code"] ?? []) ?>
</script>
<section class="calculate-section">
    <div class="div-pad" id="calculate-div1">
        <div class="ccs-logo"></div>
        <div class="student-info">
            <div class="student-info-left">
                <div class="calculate-student-name">
                    <label for="calculate-student-name">NAME:</label>
                    <input type="text" id="calculate-student-name" value="<?php echo $_SESSION['profile']['fullname']?>" readonly>
                </div>
                <div class="calculate-student-id">
                    <label for="calculate-student-id">STUDENT ID:</label>
                    <input type="text" id="calculate-student-id" value="<?php echo $_SESSION['profile']['student-id']?>" readonly>
                </div>
            </div>
            <div class="student-info-right">
                <div class="calculate-student-sy">
                    <label for="calculate-student-sy">SY:</label>
                    <input type="text" id="calculate-student-sy" value="<?php echo $_SESSION['profile']['school-year']?>" readonly>
                </div>
                <div class="calculate-student-course-year">
                    <label for="calculate-student-course-year">COURSE & YEAR:</label>
                    <input type="text" id="calculate-student-course-year" value="<?php echo $_SESSION['profile']['course'],' ', $_SESSION['profile']['year-level'] ?>" readonly>
                </div>
            </div>

        </div>
    </div>
    <div class="div-pad" id="calculate-div2">  
        <div id="calculate-sem-adviser">
            <div class="calculate-semester">
                <label for="calculate-student-semester">SEMESTER:</label>
                <input type="text" id="calculate-student-semester" value="<?php echo $_SESSION['profile']['semester']?>" readonly>
            </div>
            <div class="calculate-adviser">
                <label for="calculate-student-adviser">ADVISER:</label>
                <input type="text" id="calculate-student-adviser" value="<?php echo $_SESSION['profile']['adviser']?>" readonly>
            </div>
        </div>

        <div id="grading-headers">
            <h3>SUBJECT NAME:</h3>
            <h3>SUBJECT CODE:</h3>
            <h3>UNIT:</h3>
            <h3>RATING:</h3>
            <!-- <h3>ACTION:</h3> -->
        </div>
        <form id="grading" method="POST">
            <?php if (isset($_SESSION['course-fields'])): ?>
                <?php for ($i = 0; $i < count($_SESSION['course-fields']['subject-code']); $i++): ?>
                    <div class="subject-fields" id="row-<?= $i ?>">
                        <input type="text" name="subject-name[]" value="<?= $_SESSION['course-fields']['subject-name'][$i] ?>" readonly>
                        <input type="text" name="subject-code[]" value="<?= $_SESSION['course-fields']['subject-code'][$i] ?>" readonly>
                        <input type="number" name="unit[]" value="<?= $_SESSION['course-fields']['units'][$i] ?>" readonly>
                        <input type="number" name="grade[]" value="<?= $_SESSION['course-fields']['grade'][$i] ?? NULL?>">
                        <!-- <button type="button" class="subject-remove-buttons" onclick="removeSubjectRow(<?= $i ?>)">remove</button> -->
                    </div>
                <?php endfor; ?>
            <?php endif; ?>
        </form>
        <div id="calculate-action-buttons">
            <!-- <button type="button" id="student-calculate-add-row" onclick="addSubjectRow()">
                Add Row +
            </button> -->
            <a href="results" id="results-link"><button id="student-calculate-calculate">Calculate</button></a>   
        </div>
    </div>
</section>

<script src="/cssc/controllers/subject-process.js"></script>

