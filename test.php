<?php
session_start();
echo 'Current Server Request Method: ' . $_SERVER['REQUEST_METHOD'];
echo '<br><br>';

echo 'Session Variable: ' . $_SESSION;
echo '<br><br>';

// calculate.php
echo 'Calculate Page:';
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
echo '<br><br>';


echo '<pre>';
print_r($_FILES);
echo '</pre>';
echo '<br><br>';


echo 'getcwd' . getcwd();

// TEMPLATES
echo '<pre>';

echo '</pre>';
// ===
echo '<br><br>';

?>