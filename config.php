<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Default XAMPP user
define('DB_PASS', '');            // Default: no password
define('DB_NAME', 'fishing_api_project_db_1');

try {
    // Connect to MySQL server (no DB yet)
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create DB if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Switch to the new DB
    $pdo->exec("USE `" . DB_NAME . "`");

    // Create users table
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            salt VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            phone_number VARCHAR(20) NOT NULL
        );
    ";
    $pdo->exec($createTableQuery);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
