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
    $email = cleanInput($_SESSION['profile']['user-email']);
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
                $maxWidth = 700;
                $maxHeight = 600;

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
                echo ("<script>console.log('Resized image inserted successfully')</script>");
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
        $_SESSION['validate-button'] = "Submitted";
    } else {
        $_SESSION['validate-button'] = "Failed";
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



