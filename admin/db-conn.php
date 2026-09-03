<?php
error_reporting(E_ALL);
// session_start();


// Database Configuration
$local = true; // Set to false for live server

if ($local) {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbName = 'aristonut';
    $site = "http://localhost/office_php_project/aristonut/";
    // $site = "http://localhost/projects/aristonut/";

} else {
    $host = 'localhost';
    $username = 'u776339737_aristonut';
    $password = 'Aristo@nut1';
    $dbName = 'u776339737_aristonut';
    $site = 'https://www.aristonut.com/aristonut/';
}
// Create Database Connection
$conn = new mysqli($host, $username, $password, $dbName);

// Check Connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Optional: Set Character Encoding to UTF-8
$conn->set_charset("utf8");

?>