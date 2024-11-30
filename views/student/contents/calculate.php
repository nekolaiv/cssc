<?php 
$page_title = "calculate";
require_once('../../tools/session.function.php');
require_once ("../../includes/_student-head.php");
require_once('../../classes/student.class.php');

// $student = new Student();

// if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate-GWA']) && $_POST['calculate-GWA'] === 'calculate-GWA'){
//     $subject_codes = $_SESSION['course-fields']['subject-code'];
//     $grades = $_SESSION['course-fields']['grade'];
//     $units = $_SESSION['course-fields']['unit'];

//     for ($i = 0; $i < count($subject_codes); $i++) {
//         if ($subject_codes[$i] !== NULL && $grades[$i] !== NULL && $units[$i] !== NULL) {
//             $subject_codes[$i] = cleanInput($subject_codes[$i]);
//             $grades[$i] = cleanNumericInput($grades[$i]);
//             $units[$i] = cleanNumericInput($units[$i]);
//         }
//     }
//     $gwa_result = $student->calculateGWA($subject_codes, $grades, $units);
//     // $student_entry = $this->student->getStudentEntry($_SESSION['profile']['email']);

//     if ($gwa_result >= 1 && $gwa_result <= 2) {
//         $_SESSION['GWA'] = ['message-1' => 'Congratulations!', 'message-2' => 'You are qualified for:', 'message-3' => "Dean's Lister", 'gwa-score' => $gwa_result];
//     } else if ($gwa_result > 2 && $gwa_result <= 5) {
//         $_SESSION['GWA'] = ['message-1' => "We're sorry", 'message-2' => 'You not are qualified for:', 'message-3' => "Dean's Lister", 'gwa-score' => $gwa_result];
//     } else {
//         $_SESSION['GWA'] = ['message-1' => "Invalid Grade", 'message-2' => 'There must be a mistake with your inputs', 'message-3' => "Edit Inputs to Double Check", 'gwa-score' => $gwa_result];
//     }
// }

// print_r($_SESSION["course-fields"]["subject-code"]);
?>
<script>
    let session_length = <?= count($_SESSION["course-fields"]["subject-code"] ?? []); ?> 
</script>
<body class="home-body">
    <main class="wrapper">
        <?php require_once "../../includes/_student-header.php"?>
        <div class="content">
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
                        <h3>SUBJECT CODE:</h3>
                        <h3>UNIT:</h3>
                        <h3>RATING:</h3>
                        <h3>ACTION:</h3>
                    </div>
                    <form id="grading" method="POST">
                        <?php if (isset($_SESSION['course-fields'])): ?>
                            <?php for ($i = 0; $i < count($_SESSION['course-fields']['subject-code']); $i++): ?>
                                <div class="subject-fields" id="row-<?= $i ?>">
                                    <input type="text" name="subject-code[]" value="<?= $_SESSION['course-fields']['subject-code'][$i] ?>">
                                    <input type="number" name="unit[]" value="<?= $_SESSION['course-fields']['unit'][$i] ?>">
                                    <input type="number" name="grade[]" value="<?= $_SESSION['course-fields']['grade'][$i] ?>">
                                    <button type="button" class="subject-remove-buttons" onclick="removeSubjectRow(<?= $i ?>)">remove</button>
                                </div>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </form>
                    <div id="calculate-action-buttons">
                        <button type="button" id="student-calculate-add-row" onclick="addSubjectRow()">
                            Add Row +
                        </button>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="calculate-GWA" value="calculate-GWA">
                        <input type="file" name="image-proof" id="image-proof" accept="image/*" value="<?= $_SESSION['course-fields']['image-proof'][$i] ?? NULL ?>" title="Screenshot of your Complete Portal Grades">
                        <a id="calculate-gwa"><button type="submit" id="student-calculate-calculate">Calculate</button></a>
                    </form>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="/cssc/controllers/subject-process.js"></script>
<script src="/cssc/controllers/student-controller.js"></script>
<?php include_once "../../includes/_student-footer.php"?>



