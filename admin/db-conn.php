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
<<<<<<< HEAD
    $site = "http://localhost/projects/aristonut/";
=======
    $site = "http://localhost/office_php_project/aristonut/";
>>>>>>> 9f70d3fd0e71bcc96fe8fa18851eb6a1b9dc0084
} else {
    $host = 'localhost';
    $username = 'u776339737_aristonut';
    $password = 'UuR@~m3C2!Kj';
    $dbName = 'u776339737_aristonut';
    $site = 'https://www.aristonut.com/';
}
// Create Database Connection
$conn = new mysqli($host, $username, $password, $dbName);

// Check Connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Optional: Set Character Encoding to UTF-8
$conn->set_charset("utf8");

<<<<<<< HEAD
?>
=======
?>
>>>>>>> 9f70d3fd0e71bcc96fe8fa18851eb6a1b9dc0084
