<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}

require_once('../src/controllers/auth-controller.class.php');
require_once('../src/controllers/route-controller.class.php');

use Src\Controllers\AuthController;
use Src\Controllers\RouteController;

$auth_controller = new AuthController();
$route_controller = new RouteController();

// ================
// TESTING PURPOSES
// print_r($_POST);
// print_r($_SESSION);
// echo 'outside';
// ================

if(empty($_SESSION['action'])){
    $_SESSION['action'] = 'login';
    header('Refresh: 0');
}

if(empty($_SESSION['is-logged-in'])){
    // echo 'not logged';
    $action = $_SESSION['action'];
    switch ($action) {
        case 'login':
            $auth_controller->login();
            break;

        case 'register':
            $auth_controller->register();
            break;

        case 'forgot-password':
            $auth_controller->forgotPassword();
            break;

        case 'logout':
            $auth_controller->logout();
            break;

        default:
            header('Location: ./index.php', 'Refresh: 0');
            exit;
    }
} else if ($_SESSION['is-logged-in'] === true && isset($_SESSION['user-id'])){
    // echo 'logged';
    $user_type = $_SESSION['user-type'];
    switch ($user_type) {
        case 'student':
            $route_controller->studentMainView();
            break;

        case 'staff':
            require_once(STUDENT_DIR . 'home.php');
            break;

        case 'admin':
            require_once(STUDENT_DIR . 'home.php');
            break;
        
    }
}





?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <pre>
        <?php //print_r($_SESSION);?>
    </pre>
</body>
</html> -->

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select User</title>
    <link rel="stylesheet" href="/cssc/src/css/global.css">
</head>
<body class="tp-body">
    <section class="tp-section">
        <h1 class="tp-h1">SELECT USER TYPE</h1>
        <div class="tp-buttons">
            <a href="/cssc/src/views/student/login.php"><button class="tp-options">Student</button></a>
            <a href="/cssc/src/views/staff/login.php"><button class="tp-options">Staff</button></a>
            <a href="/cssc/src/views/admin/login.php"><button class="tp-options">Admin</button></a>
        </div>
        <p>Note: Temporary Page for Development Purposes</p>
    </section>
</body>
</html> -->