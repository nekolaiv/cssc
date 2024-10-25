<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../src/controllers/auth-controller.class.php');
require_once('../src/middlewares/auth-middleware.class.php');

use Src\Controllers\AuthController;
use Src\Middlewares\AuthMiddleware;


$auth_controller = new AuthController();
$middleware = new AuthMiddleware();

if(empty($_SESSION['is-logged-in'])){
    $_SERVER['REQUEST_URI'] = '/login';
} else {
    $_SERVER['REQUEST_URI'] = '/home';
}

$requestUri = $_SERVER['REQUEST_URI'];

switch ($requestUri) {
    case '/login':
        $auth_controller->login();
        break;

    case '/logout':
        $auth_controller->logout();
        break;

    case '/home':
        $middleware->handle();
        break;
    
    default:
        break;
}
?>

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