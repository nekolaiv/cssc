<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class SessionManager {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['is_loggedIn']);
    }

    public function logOut() {
        $_SESSION = [];
        session_destroy();
    }

    public function regenerateSession() {
        if (session_id()) {
            session_regenerate_id(true);
        }
    }

    public function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }

    public function initializeSession($userId, $userRole) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_role'] = $userRole;
        $_SESSION['is_loggedIn'] = true; // Set login status
    }

    public function isSessionExpired($timeoutDuration) {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeoutDuration) {
            $this->logOut();
        }
        $_SESSION['last_activity'] = time();
    }
}

// Usage
// $sessionManager = new SessionManager();
