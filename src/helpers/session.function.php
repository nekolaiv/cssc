<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['is_loggedIn']);
}

// Function to get the user's role
function getUserRole() {
    return $_SESSION['user_role'] ?? null; // Return user role if set, otherwise null
}

// Function to initialize a session variable after login
function initializeSession($userId, $userRole) {
    $_SESSION['user_id'] = $userId; // Store user ID
    $_SESSION['user_role'] = $userRole; // Store user role
}

// Function to log out a user
function logOut() {
    $_SESSION = []; // Clear all session variables
    session_destroy(); // Destroy the session
}

// Optional: Function to regenerate session ID for security
function regenerateSession() {
    if (session_id()) {
        session_regenerate_id(true); // Replace the current session ID with a new one
    }
}

// Optional: Function to check session timeout
function isSessionExpired($timeoutDuration) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeoutDuration) {
        logOut(); // Log out if the session has expired
    }
    $_SESSION['last_activity'] = time(); // Update last activity timestamp
}
?>
