<?php

require_once '../classes/_admin.class.php';


$admin = new Admin(); // Assuming the Admin class is properly included and initialized

// Replace these with actual values for testing
$plainPassword = "PAPA"; // Replace with the password you want to test
$hashedPassword = '$2y$10$dFcuJ5KJL56wQhoAD75fPOZf3NnNcJp5/bEQyT6ZbcHv8LLcmML1W'; // Replace with the hash from your database

if ($admin->verifyPassword($plainPassword, $hashedPassword)) {
    echo "Password matches!";
} else {
    echo "Password does not match.";
}
