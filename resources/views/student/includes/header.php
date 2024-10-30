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
    
</head>
<body class="home-body">
    <header>
        <p class="logo"><a onclick="loadPage('home.php')">CSSC</a></p>
        <button class="menu-toggle" id="menu-toggle">
            <span class="menu-icon"></span>
        </button>
        <nav class="nav-menu" id="nav-menu">
            <ul>
                <button onclick="loadPage('home.php')">Home</button>
                <button onclick="loadPage('about.php')">About</button>
                <button onclick="loadPage('profile.php')">Profile</button>
                <button onclick="loadPage('contact.php')">Contact</button>
            </ul>
            <form action="" method="POST">
                <!-- <input type="hidden" name="action" value="logout"> -->
                <button type="submit" name="logout" id="logout-button">Logout</button>
            </form>
        </nav>
    </header>
    <script src="/cssc/resources/js/hamburger.js"></script>