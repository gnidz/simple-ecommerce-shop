<?php
// Database connection details
$host = 'localhost'; // Database server hostname
$dbname = 'ecommerce';        // Database name
$user = 'root';          // Database username
$password = '';            // Database password

try {
    // Create a new PDO instance
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8"; // DSN with UTF-8 charset
    $pdo = new PDO($dsn, $user, $password);

    // Set PDO error mode to exception for better debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Uncomment this line to confirm connection during testing
    // echo "Connected successfully";
} catch (PDOException $e) {
    // Handle connection error
    die("Database connection failed: " . $e->getMessage());
}
?>
