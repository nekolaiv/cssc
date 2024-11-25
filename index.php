<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('./tools/session.function.php');

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}

$uri .= $_SERVER['HTTP_HOST'];

$uri .= $_SERVER['REQUEST_URI'];

header('Location: ' . $uri . 'auth/login.php');

?>