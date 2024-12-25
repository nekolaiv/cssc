<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/includes/_student-head.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');

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
    <title><?=$page_title?></title>
    <link rel="stylesheet" href="/cssc/vendor/bootstrap-5.3.3/css/bootstrap.css">
    <link rel="stylesheet" href="/cssc/css/global.css">
    <link rel="stylesheet" href="/cssc/css/student.css">
    <script src="/cssc/vendor/jquery-3.7.1/jquery-3.7.1.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
</head>
