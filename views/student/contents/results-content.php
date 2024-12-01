<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "results";


require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');

?>
<div id="result-section">
    <h2 id="result-message-1"><?php echo $_SESSION['GWA']['message-1'] ?? "None"?></h2>
    <h2 id="result-message-2"><?php echo $_SESSION['GWA']['message-2'] ?? "None"?></h2>
    <h2 id="result-message-3"><?php echo $_SESSION['GWA']['message-3'] ?? "None"?></h2>
    <h2 id="result-message-4">GWA SCORE: <?php echo $_SESSION['GWA']['gwa-score'] ?? "None"?></h2>
    <h2 id="result-verification-status">Verification Status: 
        <?php echo $_SESSION['profile']['status'] ?? "None";?>
    </h2>
    <div id="result-action-buttons">
        <a href="home" id="result-home-link" class="nav-items"><button>Home</button></a>
        <a href="calculate" id="calculate-link" class="nav-items"><button>Edit Inputs</button></a>
        <form id="validation-buttons" action="" method="POST" enctype="multipart/form-data">
            <button type="submit" name="validate-button" id="validate-button"> <?php echo $_SESSION['validate-button'] ?? "Validate Entry" ?></button>
            <input type="file" name="image-proof" id="image-proof" accept="image/*" title="Screenshot of your Complete Portal Grades" required>
        </form>
        <?php if(isset($image_proof)){
            // echo '<img src="data:image/jpeg;base64,' . base64_encode($_SESSION['image-proof']) . '" />';
            
        } ?>
        <!-- <img src="data:image/jpeg;base64, <?= base64_encode($_SESSION['image-proof']) ?>" /> -->
    </div>
</div>
<script src="/cssc/controllers/subject-process.js"></script>
<!-- <script src="/cssc/controllers/student-controller.js"></script> -->