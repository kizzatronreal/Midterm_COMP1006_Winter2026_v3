<?php
require "includes/connect.php";

// get review ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('Invalid review ID');
}

// Delete the review
try {
    $sql = "DELETE FROM reviews WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    // redirect back to admin page
    header("Location: admin.php");
    exit;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
