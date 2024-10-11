<?php 
ob_start();

require_once "../classes/auth.class.php";
$auth = new AdminClass();
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