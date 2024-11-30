<?php

require_once '../classes/_admin.class.php';


$admin = new Admin(); // Assuming the Admin class is properly included and initialized

// Replace these with actual values for testing
$plainPassword = "ahmad12345"; // Replace with the password you want to test
$hashedPassword = '$2y$10$M9zGhZcF9VzYBuPt8G4kU.FEKFTHr48.j43RFaAw096OOTSkXYkCG'; // Replace with the hash from your database

if ($admin->verifyPassword($plainPassword, $hashedPassword)) {
    echo "Password matches!";
} else {
    echo "Password does not match.";
}
