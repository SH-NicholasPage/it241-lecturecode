<?php
// config.php - local dev only
$dbHost = 'localhost'; // or 127.0.0.1
$dbUser = ''; // TODO - username
$dbPass = 'IT241Pwd!';
$dbName = ''; // TODO - database name

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_errno)
{
    die('Connect error: ' . $conn->connect_error);
}

