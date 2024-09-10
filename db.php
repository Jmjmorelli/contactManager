<?php
// Database connection settings
$servername = "localhost"; // Replace with your database server name
$username = "ADMIN"; // Replace with your database username
$password = "jupiter"; // Replace with your database password
$dbname = "contact_manager"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
