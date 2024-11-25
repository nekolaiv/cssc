<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../tools/session.function.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout']) && $_POST['logout'] === 'logout'){
    logOut();
    header('Location: ../../auth/login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="/csrs/css/global.css">
    <link rel="stylesheet" href="/csrs/css/student.css">
    <script src="/csrs/vendor/jquery-3.7.1/jquery-3.7.1.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
</head>
<body class="home-body">
    <header>
        <p class="logo"><a onclick="loadPage('home.php')">CSRS</a></p>
        <button class="menu-toggle" id="menu-toggle">
            <span class="menu-icon"></span>
        </button>
        <nav class="nav-menu" id="nav-menu">
            <ul>
                <button class="nav-items" id="home-link">Home</button>
                <button class="nav-items" id="leaderboard-link">Leaderboard</button>
                <button class="nav-items" id="about-link">About</button>
            </ul>
            <div class="notification-bell">
                <span class="badge">3</span> <!-- Change this number dynamically with PHP -->
            </div>
            <div class="profile-icon" id="profile-icon">
                <div class="dropdown" id="profile-dropdown">
                    <button class="nav-items">profile</button>
                    <button class="nav-items">settings</button>
                    <form action="" method="POST">
                        <!-- <input type="hidden" name="action" value="logout"> -->
                        <button type="submit" name="logout" value="logout" id="logout-button">Logout</button>
                    </form>
                </div>
            </div>
            
        </nav>
    </header>
    <script src="/cssc/resources/js/hamburger-responsive.js"></script>
    <script src="/cssc/resources/js/profile-dropdown.js"></script>