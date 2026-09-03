<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Global Session Cookies Scope Path Locking Layout Strategy
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Database Configuration
$local = true; // Set to false for live server

if ($local) {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbName = 'aristonut';
    // $site = "http://localhost/projects/aristonut/";
    $site = "http://localhost/office_php_project/aristonut/";
} else {
    $host = 'localhost';
    $username = 'u776339737_aristonut';
    $password = 'Aristo@nut1';
    $dbName = 'u776339737_aristonut';
    $site = 'https://www.aristonut.com/aristonut/';
}

global $site;

// Create Database Connection
$conn = new mysqli($host, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>