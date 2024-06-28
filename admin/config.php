<?php
  
$hostname = "localhost";
$username = "root";
$password = "";
$dbName = "forum";

try {
    $conn = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}