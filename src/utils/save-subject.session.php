<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Initialize $_SESSION array if not already set
if (!isset($_SESSION['course-fields'])) {
    $_SESSION['course-fields'] = [];
}

// Save form data to the session (subject-code, unit, grade, etc.)
foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        $_SESSION['course-fields'][$key] = $value; // Preserve array format
    } else {
        $_SESSION['course-fields'][$key] = [$value]; // Ensure it's an array if it's not already
    }
}

// Handle image upload (file upload handling)
if (isset($_FILES['image-proof']) && !empty($_FILES['image-proof']['name'][0])) {
    $imageFiles = $_FILES['image-proof'];

    // Initialize an array to store base64-encoded image data
    $uploadedImages = [];
    
    // Loop over all uploaded files (in case there are multiple files)
    foreach ($imageFiles['name'] as $index => $tmpName) {
        if ($imageFiles['error'][$index] === UPLOAD_ERR_OK) {
            // Read the uploaded file into a variable (binary data)
            $imageData = file_get_contents($tmpName);
            // Encode it as base64 to store in the session
            $uploadedImages[] = base64_encode($imageData);
        } else {
            // If there is an error with the upload, handle it
            echo json_encode(["error" => "There was an error uploading the file."]);
            exit;
        }
    }

    // Store the uploaded images in the session
    if (!empty($uploadedImages)) {
        $_SESSION['course-fields']['image-proof'] = $uploadedImages;
    }
} else {
    echo "<script>alert('File not uploaded or file input is empty');</script>";
}

// Return session data as JSON response (for debugging or future use)
echo json_encode($_SESSION);
?>
