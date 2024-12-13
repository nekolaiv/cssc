<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "previous";


require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/clean.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');

$student = new Student();
$previous_result = $student->getStudentSubmittedGWA($_SESSION['profile']['email']);
if($previous_result){
    $_SESSION['previous-GWA'] = ['message-1' => 'Congratulations!', 'message-2' => 'You are qualified for:', 'message-3' => "Dean's Lister", 'gwa-score' => $previous_result ?? NULL];

} else {
    $_SESSION['previous-GWA'] = ['message-1' => 'No Entry Found!', 'message-2' => 'You haven\'t submitted any entries', 'message-3' => "Calculate your grades and submit", 'gwa-score' => 'None'];
}
?>
<div id="result-section">
    <h2 id="result-message-1"><?php echo $_SESSION['previous-GWA']['message-1'] ?? "None"?></h2>
    <h2 id="result-message-2"><?php echo $_SESSION['previous-GWA']['message-2'] ?? "None"?></h2>
    <h2 id="result-message-3"><?php echo $_SESSION['previous-GWA']['message-3'] ?? "None"?></h2>
    <h2 id="result-message-4">GWA SCORE: <?php echo $_SESSION['previous-GWA']['gwa-score'] ?? "None"?></h2>
    <h2 id="result-verification-status">Verification Status: 
        <?php echo $_SESSION['profile']['status'] ?? "None";?>
    </h2>
    <div id="result-action-buttons">
        <a href="results" id="results-link" class="nav-items"><button>Show Recent Results</button></a>
    </div>
</div>
<script src="/cssc/controllers/subject-process.js"></script>
<!-- <script src="/cssc/controllers/student-controller.js"></script> -->