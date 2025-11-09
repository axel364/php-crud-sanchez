<?php
$server = 'localhost';
$database = 'branch_directory';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host={$server};dbname={$database}",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $error) {
    echo 'Database connection failed: ' . $error->getMessage();
    exit;
}
?>
