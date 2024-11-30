<?php
// // Include the necessary class files
// require_once 'Database.php';
// require_once 'Image.php';

// // Create a database connection instance
// $student = new Student();

// $image_proof = $student->getStudentImageProof($email);

// if ($image_proof) {
//     // Set the appropriate headers for the image
//     // We need to determine the MIME type for the image based on its data
//     $finfo = finfo_open(FILEINFO_MIME_TYPE); // Get MIME type
//     $mimeType = finfo_buffer($finfo, $image_proof['image_proof']);
//     finfo_close($finfo);

//     // Set headers for displaying the image
//     // header("Content-Type: $mimeType");  // Set the correct MIME type
//     // header("Content-Disposition: inline; filename=" . $image_proof['name']);  // Display inline
//     echo $image_proof;  // Output the binary image data
// } else {
//     echo "Image not found.";
// }

?>
