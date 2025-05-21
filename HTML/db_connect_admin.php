<?php
$servername = "localhost";  // Change if your database is hosted remotely
$username = "root";         // Default XAMPP user
$password = "";             // Default XAMPP password (empty)
$database = "fitness_app";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
