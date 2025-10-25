<?php
// Include Composer autoload (for Dotenv)
require __DIR__ . '/vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Read database credentials from environment variables
$host = $_ENV['DB_HOST'];     // database host, e.g., localhost
$db   = $_ENV['DB_NAME'];     // database name
$user = $_ENV['DB_USER'];     // database username
$pass = $_ENV['DB_PASS'];     // database password

try {
    // Create a new PDO instance
    // charset=utf8 ensures proper handling of special characters
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Set PDO error mode to Exception — helpful for debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // If connection fails, stop execution and show error
    die("Connection Failed: " . $e->getMessage());
}
?>