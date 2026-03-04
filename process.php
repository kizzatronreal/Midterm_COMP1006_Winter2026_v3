<?php
require "includes/header.php";
require "includes/connect.php";

// Check form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
    $author = trim(filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS));
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $review_text = trim(filter_input(INPUT_POST, 'review_text', FILTER_SANITIZE_SPECIAL_CHARS));

    // Validation
    $errors = [];
    
    if (empty($title)) {
        $errors[] = "Book title is required";
    }
    if (empty($author)) {
        $errors[] = "Author is required";
    }
    if (!$rating || $rating < 1 || $rating > 5) {
        $errors[] = "Rating must be between 1 and 5";
    }
    if (empty($review_text)) {
        $errors[] = "Review text is required";
    }

    // If no errors, insert into database
    if (empty($errors)) {
        try {
            $sql = "
                INSERT INTO reviews (title, author, rating, review_text)
                VALUES (:title, :author, :rating, :review_text)
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':author' => $author,
                ':rating' => $rating,
                ':review_text' => $review_text
            ]);
            $message = "Review submitted successfully!";
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>

    <h1>Review Submission Result</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h4>Validation Errors:</h4>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <p>
            <a href="index.php" class="btn">Back to Form</a>
        </p>
    <?php elseif (isset($message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($message); ?>
        </div>
        <p>
            <a href="index.php" class="btn">Submit Another Review</a>
            <a href="admin.php" class="btn btn-secondary">View All Reviews</a>
        </p>
    <?php endif; ?>

<?php require "includes/footer.php"; ?>
