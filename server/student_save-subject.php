<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../tools/session.function.php');

class CourseFormHandler
{
    private $sessionKey = 'course-fields';
    private $db;

    public function __construct()
    {
        // Initialize the session if not already set
        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = [];
        }
    }

    // Method to save form data to the session
    public function saveFormData($postData){
        foreach ($postData as $key => $value) {
            if (is_array($value)) {
                $_SESSION[$this->sessionKey][$key] = $value; // Preserve array format
            } else {
                $_SESSION[$this->sessionKey][$key] = [$value]; // Ensure it's an array if it's not already
            }
        }
        return true;
    }

    // Method to handle image uploads anxd save them as BLOB in the database
    public function handleImageUpload($fileData){
        if (isset($fileData['image-proof']) && !empty($fileData['image-proof']['name'][0])) {
            $imageFiles = $fileData['image-proof'];

            // Loop over all uploaded files (in case there are multiple files)
            foreach ($imageFiles['name'] as $index => $tmpName) {
                if ($imageFiles['error'][$index] === UPLOAD_ERR_OK) {
                    $imageData = file_get_contents($tmpName);
                    $imageBlob = base64_encode($imageData); // Encode as base64 to save in session or database

                    // Example of saving to database as BLOB:
                    // $stmt = $this->db->prepare("INSERT INTO images (image_data) VALUES (?)");
                    // $stmt->bind_param("s", $imageBlob);
                    // $stmt->execute();
                    // $stmt->close();

                    // Alternatively, you can save the image in session
                    $_SESSION[$this->sessionKey]['image-proof'][] = $imageBlob;
                } else {
                    echo json_encode(["error" => "There was an error uploading the file."]);
                    exit;
                }
            }
        } else {
            echo "<script>alert('File not uploaded or file input is empty');</script>";
        }
    }

    // Method to return session data as a JSON response
    public function getSessionData(){
        return json_encode($_SESSION);
    }
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Instantiate the handler class
    $courseFormHandler = new CourseFormHandler();

    // Save form data
    if($courseFormHandler->saveFormData($_POST)){
        echo json_encode(["success" => "Data saved successfully."]);
    }

    // Handle image upload (store in session or database as BLOB)
    // $courseFormHandler->handleImageUpload($_FILES);

    // Output session data as JSON (for debugging or further use)
    
} else {
    // If it's not a POST request, return an error
    echo json_encode(["error" => "Invalid request method."]);
}
?>
