<?php 
session_start();
$_SESSION['course-fields'] = $_POST;
echo json_encode($_SESSION);
?>