<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$auth_files = ['login.php', 'register.php', 'forgot-password.php'];
$current_file = basename($_SERVER['PHP_SELF']);

if (!isLoggedIn() && !in_array($current_file, $auth_files)) {
    header("Location: /cssc/auth/login.php");
    exit;
} else if(isLoggedIn() && in_array($current_file, $auth_files)) {
    $user_type = getUserType();
    if ($user_type === "student") {
        echo '<script type="text/javascript">window.location.href = "../views/student/home";</script>';
        exit;
    } else if ($user_type === 'staff') {
        header('Location: /cssc/views/staff/index.php');
        exit;
    } else if ($user_type === 'admin'){
        header('Location: /cssc/views/admin/index.php');
        exit;
    }
}
function isLoggedIn() {
    return isset($_SESSION['user-id']) && isset($_SESSION['is-logged-in']);
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

function getUserType() {
    return $_SESSION['user-type'] ?? null;
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
