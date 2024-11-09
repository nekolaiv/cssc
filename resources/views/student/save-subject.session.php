<?php
session_start();
$input = json_decode(file_get_contents('php://input'), true);
if (is_array($input)) {
    $_SESSION['subjects'] = $input;
    echo "<script> alert('hacked'); </script>";
} else {
    echo json_encode(['status' => 'error']);
}
?>