<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['is_loggedIn']);
}

function regenerateSession() {
    if (session_id()) {
        session_regenerate_id(true);
    }
}

function generateCSRF(){
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(string: random_bytes(length: 32));
    }
}

function logOut() {
    $_SESSION = [];
    session_destroy();
}

function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

function initializeSession($userId, $userRole) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_role'] = $userRole;
    $_SESSION['is_loggedIn'] = true; // Set login status
}

function isSessionExpired($timeoutDuration) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeoutDuration) {
        logOut();
    }
    $_SESSION['last_activity'] = time();
}
