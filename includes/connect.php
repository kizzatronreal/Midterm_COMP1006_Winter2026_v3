<?php
// Database connection using PDO
try {
    $pdo = new PDO("mysql:host=localhost;dbname=book_manager", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
