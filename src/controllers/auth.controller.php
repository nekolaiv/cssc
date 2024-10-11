<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

require_once "../classes/auth.class.php";
$auth = new Auth();
$action = isset($_GET["action"]) ? $_GET["action"] : ""; // Alternative: could be post

switch ($action) {
  case "student_login":
    $auth->studentLogin();
    break;
  case "staff_login":
    $auth->staffLogin();
    break;
  case "admin_login":
    $auth->adminLogin();
    break;
  case "logout":
    $auth->logOut();
    break;
}

ob_end_flush();
?>