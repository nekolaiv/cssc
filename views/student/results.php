<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "results";

require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/clean.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');

$student = new Student();
$_SESSION['validate-button'] = "Submit";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['validate-button'] )){
   
    // $folder = '/cssc/assets/student';
    $gwa_result = cleanInput($_SESSION['GWA']['gwa-score']);

    // $destination_path = getcwd().DIRECTORY_SEPARATOR;
    // $target_path = $destination_path . basename( $_FILES["image-proof"]["name"]);

    try {
    if (isset($_FILES['image-proof']) && $_FILES['image-proof']['error'] === UPLOAD_ERR_OK) {
        $imagePath = $_FILES['image-proof']['tmp_name'];
        $imageType = exif_imagetype($imagePath);

        // Validate image type (only jpeg, png, gif)
        if (in_array($imageType, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF])) {
            // Read image data into a variable for database storage
            $imageData = file_get_contents($imagePath);

            // Save image data to the database
            $result = $student->saveEntryToDatabase($gwa_result, $imageData);
            if ($result) {
                $_SESSION['validate-button'] = "Submitted";
            } else {
                $_SESSION['validate-button'] = "Failed";
            }
        } else {
            echo json_encode(["error" => "Invalid image format"]);
        }
    } else {
        echo json_encode(["error" => "No uploaded file or there was an error"]);
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


    // $image_proof = $student->getStudentImageProof($email);
    // $_SESSION['image-proof'] = $image_proof;

}
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



