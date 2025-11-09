<?php
const DB_HOST = 'localhost';
const DB_NAME = 'branch_directory';
const DB_USER = 'root';
const DB_PASS = '';

/**
 * @return PDO|null
 */
function connectToDatabase(): ?PDO
{
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage()); 
        return null;
    }
}

$pdo = connectToDatabase();

if ($pdo === null) {
    die("Failed to connect to the database. Check error logs for details.");
}

?>