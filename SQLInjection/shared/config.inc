<?php
// config.php - local dev only
$dbHost = '10.0.2.2'; // localhost
$dbUser = 'nick';
$dbPass = 'IT241Pwd!';
$dbName = 'BadSocial';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_errno)
{
    die('Connect error: ' . $conn->connect_error);
}

