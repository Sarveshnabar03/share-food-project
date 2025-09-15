<?php
// Database credentials
$host = 'localhost';      // Usually localhost
$db   = 'sharefood';      // Your database name
$user = 'root';           // Your MySQL username
$pass = '';               // Your MySQL password
$charset = 'utf8mb4';

// Set DSN
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // show errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch assoc arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // native prepares
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options); // create PDO object
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>
