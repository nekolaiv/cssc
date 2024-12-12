<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "results";

// require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/clean.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');

// $student = new Student();

// $previous_result = $student->getStudentSubmittedGWA($_SESSION['profile']['email']);
// if($previous_result){
//     $_SESSION['previous-GWA'] = ['message-1' => 'Congratulations!', 'message-2' => 'You are qualified for:', 'message-3' => "Dean's Lister", 'gwa-score' => $previous_result ?? NULL];

// } else {
//     $_SESSION['previous-GWA'] = ['message-1' => 'No Entry Found!', 'message-2' => 'You haven\'t submitted any entries', 'message-3' => "Calculate your grades and submit", 'gwa-score' => 'None'];
// }

?>

<body class="home-body">
    <main class="wrapper">
        <?php 
        require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-header.php');
        ?>
        <div class="content">
            
        </div>
    </main>


<script src="/cssc/controllers/student-controller.js"></script>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-footer.php');?>



