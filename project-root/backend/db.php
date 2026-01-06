<?php
    // Data base details
    $host = 'localhost';
    $db   = 'movie';
    $user = 'root';
    $pass = '1234';

    // Creating the connection using PDO
    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8mb4",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] // Exception handeling
        );
    } catch (PDOException $e) {
        die("DB Connection failed: " . $e->getMessage());
    }
?>