<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "results";
include_once("../../includes/_student-head.php");
require_once("../../classes/student.class.php");
require_once('../../tools/clean.function.php');

$student = new Student();
$validate_button = "Validate";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['validate-button'] )){
   
    // $folder = '/cssc/assets/student';
    $email = cleanInput($_SESSION['profile']['email']);
    $gwa_result = cleanInput($_SESSION['GWA']['gwa-score']);

    // $destination_path = getcwd().DIRECTORY_SEPARATOR;
    // $target_path = $destination_path . basename( $_FILES["image-proof"]["name"]);

    try {
        if (isset($_FILES['image-proof']) && $_FILES['image-proof']['error'] === UPLOAD_ERR_OK) {
            // Load the image
            $imagePath = $_FILES['image-proof']['tmp_name'];
            $imageType = exif_imagetype($imagePath);

            // Validate image type (only jpeg, png, gif)
            if (in_array($imageType, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF])) {

                // Set target dimensions for resizing (for example, 800x600)
                $maxWidth = 400;
                $maxHeight = 400;

                // Create image resource based on the type
                switch ($imageType) {
                    case IMAGETYPE_JPEG:
                        $image = imagecreatefromjpeg($imagePath);
                        break;
                    case IMAGETYPE_PNG:
                        $image = imagecreatefrompng($imagePath);
                        break;
                    case IMAGETYPE_GIF:
                        $image = imagecreatefromgif($imagePath);
                        break;
                    default:
                        throw new Exception("Unsupported image type.");
                }

                // Get original dimensions
                $originalWidth = imagesx($image);
                $originalHeight = imagesy($image);

                // Calculate new dimensions while preserving aspect ratio
                $aspectRatio = $originalWidth / $originalHeight;

                if ($originalWidth > $originalHeight) {
                    $newWidth = $maxWidth;
                    $newHeight = $maxWidth / $aspectRatio;
                } else {
                    $newHeight = $maxHeight;
                    $newWidth = $maxHeight * $aspectRatio;
                }

                // Create a new image resource with the new dimensions
                $resizedImage = imagecreatetruecolor((int)$newWidth, (int)$newHeight);

                // Preserve transparency for PNG and GIF
                if ($imageType == IMAGETYPE_PNG) {
                    imagealphablending($resizedImage, false);
                    imagesavealpha($resizedImage, true);
                }

               imagecopyresampled(
                    $resizedImage, 
                    $image, 
                    0, 0, 0, 0, 
                    (int)$newWidth, 
                    (int)$newHeight, 
                    (int)$originalWidth, 
                    (int)$originalHeight
                );
                // Compress the image to fit within a target file size (e.g., 100 KB)
                $targetFileSize = 100 * 1024; // 100 KB in bytes
                $quality = 90;  // Initial quality (for JPEG)

                // Create a temporary buffer to store the image
                ob_start();
                switch ($imageType) {
                    case IMAGETYPE_JPEG:
                        // Compress the image to a temporary buffer
                        imagejpeg($resizedImage, null, $quality);
                        break;
                    case IMAGETYPE_PNG:
                        // PNG is lossless, so we can only reduce the compression level
                        imagepng($resizedImage, null, 9); // max compression
                        break;
                    case IMAGETYPE_GIF:
                        imagegif($resizedImage, null);
                        break;
                }

                // Get the image data from the buffer
                $imageData = ob_get_contents();
                ob_end_clean();

                // Check if the file is too large and compress more if necessary (for JPEG)
                if (strlen($imageData) > $targetFileSize && $imageType == IMAGETYPE_JPEG) {
                    // Reduce quality in steps until the file size is below the target size
                    while (strlen($imageData) > $targetFileSize && $quality > 10) {
                        $quality -= 10;
                        ob_start();
                        imagejpeg($resizedImage, null, $quality);
                        $imageData = ob_get_contents();
                        ob_end_clean();
                    }
                }


                echo ("<script>console.log('Resized image inserted successfully')</script>;");
            } else {
                echo "Invalid image format.";
            }
            // Get file details
            // Read the file content into a variable
            // $image = file_get_contents($fileTmpName);

        } else {
            echo "No file uploaded or there was an error.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    
    

    $result = $student->saveEntryToDatabase($email, $gwa_result, $imageData);
    if($result){
        $validate_button = "Submitted";
    } else {
        $validate_button = "Failed";
    }

    $image_proof = $student->getStudentImageProof($email);
    
    // $_SESSION['image-proof'] = $student->getStudentImageProof($email);
    // if(@move_uploaded_file($_FILES['image-proof']['tmp_name'], $target_path)){
    //     $validate_button = "Validated";
    // } else{
    //     $validate_button = "Failed";
    // }
    // if ($_FILES['image-proof']['error'] !== UPLOAD_ERR_OK) {
    //     echo "Upload failed with error code " . $_FILES['image-proof']['error'];
    //     $validate_button = "Failed";
    // }
}

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // Check if a file is uploaded
    
// }


// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image-proof'])) {
//     $uploadDir = '/cssc/assets/student/';
//     $destination_path = getcwd().DIRECTORY_SEPARATOR;
//     $uploadFile = $destination_path . basename($_FILES['image-proof']['name']);
    
    
//     // Ensure the target directory exists
//     // if (!is_dir($uploadDir)) {
//     //     mkdir($uploadDir, 0777, true);
//     //     echo'directory created';
//     // }

//     // Check for errors in the upload process
//     if ($_FILES['image-proof']['error'] !== UPLOAD_ERR_OK) {
//         die("Error during file upload. Error code:\n" . $_FILES['image-proof']['error']);
//     }

//     // Validate image type (to prevent upload of non-images)
//     $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
//     $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
//     if (!in_array($imageFileType, $allowedTypes)) {
//         die("Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.");
//     }

//     // Check the file size (e.g., 5MB max)
//     // if ($_FILES['image-proof']['size'] > 5 * 1024 * 1024) {
//     //     die("File is too large. Maximum size is 5MB.");
//     // }

//     // Generate a unique filename to prevent overwriting existing files
//     // $newFileName = uniqid('img_', true) . '.' . $imageFileType;
//     $targetPath = $uploadFile;

//     // Move the uploaded file to the target directory

//     if (!is_writable($targetPath)) {
//         chmod($targetPath, 755); // or 777 if necessary
//     }
//     if (move_uploaded_file($_FILES['image-proof']['tmp_name'], $targetPath)) {
//         echo "File uploaded successfully!";
//     } else {
//         echo "Error moving the file.";
//     }
// }


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
                    <a href="home" id="result-home-link" class="nav-items"><button>Home</button></a>
                    <a href="calculate" id="result-calculate-link" class="nav-items"><button>Edit Inputs</button></a>
                    <form id="validation-buttons" action="" method="POST" enctype="multipart/form-data">
                        <button type="submit" name="validate-button" id="validate-button"> <?php echo $validate_button ?? "Validate Entry" ?></button>
                        <input type="file" name="image-proof" id="image-proof" accept="image/*" value="<?= $_SESSION['image-proof'] ?? NULL ?>" title="Screenshot of your Complete Portal Grades" required>
                    </form>
                    <?php if(isset($image_proof)){
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($image_proof) . '" />';
                    } ?>
                </div>
            </div>
        </div>
    </main>


<script src="/cssc/controllers/student-controller.js"></script>

<?php include_once "../../includes/_student-footer.php"?>



