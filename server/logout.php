<?php
// logout.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clear all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    header('Location: /cssc/auth/login.php');
    exit;
}
?>
