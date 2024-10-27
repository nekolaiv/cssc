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
</head>
<body class="home-body">
    <header>
        <p class="logo">CSSC</p>
        <nav>
            <p>navbar</p>
            <form action="" method="POST">
                <!-- <input type="hidden" name="action" value="logout"> -->
                <button type="submit" name="logout">Logout</button>
            </form>
        </nav>
    </header>