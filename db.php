<?php
// Database connection details
$host = 'localhost'; // Database server hostname
$dbname = 'ecommerce';        // Database name
$user = 'root';          // Database username
$password = '';            // Database password

// Create a new MySQLi connection object
$conn = new mysqli($host, $user, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>