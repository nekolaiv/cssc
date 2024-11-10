<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// require_once "../../helpers/session.function.php";

// if (!isLoggedIn()) {
//   header("location: ./login.php");
//   exit;
// }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="/cssc/resources/css/global.css">
    <link rel="stylesheet" href="/cssc/resources/css/student.views.css">
<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
</head>
<body class="home-body">
    <header>
        <p class="logo"><a onclick="loadPage('home.php')">CSRS</a></p>
        <button class="menu-toggle" id="menu-toggle">
            <span class="menu-icon"></span>
        </button>
        <nav class="nav-menu" id="nav-menu">
            <ul>
                <button class="nav-items" onclick="loadPage('home.php')">Home</button>
                <button class="nav-items" onclick="loadPage('leaderboard.php')">Leaderboard</button>
                <button class="nav-items" onclick="loadPage('about.php')">About</button>
            </ul>
            <div class="notification-bell">
                <span class="badge">3</span> <!-- Change this number dynamically with PHP -->
            </div>
            <div class="profile-icon" id="profile-icon">
                <div class="dropdown" id="profile-dropdown">
                    <button class="nav-items" onclick="loadPage('profile.php')">profile</button>
                    <button class="nav-items" onclick="loadPage('settings.php')">settings</button>
                    <form action="" method="POST">
                        <!-- <input type="hidden" name="action" value="logout"> -->
                        <button type="submit" name="logout" value="logout" id="logout-button">Logout</button>
                    </form>
                </div>
            </div>
            
        </nav>
    </header>
    <script src="/cssc/resources/js/hamburger.js"></script>
    <script src="/cssc/resources/js/student.script.js"></script>