<?php 
$page_title = "results";
include_once("../../includes/_student-head.php");
require_once("../../classes/student.class.php");
require_once('../../tools/clean.function.php');

$student = new Student();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['validate-button'] )){
    $file_name = $_FILES['image-proof']['name'];
    $temp_name = $_FILES['image-proof']['tmp_name'];
    $folder = 'Images/'. $file_name;
    $email = cleanInput($_SESSION['profile']['email']);
    $gwa_result = cleanInput($_SESSION['GWA']['gwa-score']);

    $student->saveEntryToDatabase($_SESSION['profile']['email'], $_SESSION['GWA']['gwa-score'], $file_name);
}

?>

<body class="home-body">
    <main class="wrapper">
        <?php include_once "../../includes/_student-header.php"?>
        <div class="content">
            <div id="result-section">
                <h2 id="result-message-1"><?php echo $_SESSION['GWA']['message-1']?></h2>
                <h2 id="result-message-2"><?php echo $_SESSION['GWA']['message-2']?></h2>
                <h2 id="result-message-3"><?php echo $_SESSION['GWA']['message-3']?></h2>
                <h2 id="result-message-4">GWA SCORE: <?php echo $_SESSION['GWA']['gwa-score']?></h2>
                <h2 id="result-verification-status">Verification Status: 
                    <?php echo $_SESSION['profile']['status'];?>
                </h2>
                <div id="result-action-buttons">
                    <a href="home" id="home-link" class="nav-items"><button>Home</button></a>
                    <a href="calculate" id="calculate-link" class="nav-items"><button>Edit Inputs</button></a>
                    <form id="validation-buttons" action="" method="POST" enctype="multipart/form-data">
                        <button type="submit" id="validate-button" onclick="location.reload();"> <?php echo $validate_button ?? "Validate Entry" ?></button>
                        <input type="file" name="image-proof" id="image-proof" accept="image/*" value="<?= $_SESSION['course-fields']['image-proof'][$i] ?? NULL ?>" title="Screenshot of your Complete Portal Grades" required>
                    </form>
                </div>
            </div>
        </div>
    </main>


<script src="/cssc/controllers/student-controller.js"></script>

<?php include_once "../../includes/_student-footer.php"?>



