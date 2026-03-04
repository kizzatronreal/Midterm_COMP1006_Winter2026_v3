<?php
require "includes/header.php";
require "includes/connect.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('Invalid review ID');
}

// fetch the review
$sql = "SELECT * FROM reviews WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$review = $stmt->fetch();

if (!$review) {
    die('Review not found');
}

// Handle form submission
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

    // If no error update database
    if (empty($errors)) {
        try {
            $sql = "
                UPDATE reviews SET
                    title = :title,
                    author = :author,
                    rating = :rating,
                    review_text = :review_text
                WHERE id = :id
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':author' => $author,
                ':rating' => $rating,
                ':review_text' => $review_text,
                ':id' => $id
            ]);
            $message = "Review updated successfully!";
            // Refresh review data
            $review = [
                'id' => $id,
                'title' => $title,
                'author' => $author,
                'rating' => $rating,
                'review_text' => $review_text
            ];
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

    <h1>Edit Review</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h4>Validation Errors:</h4>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (isset($message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="edit.php?id=<?= $review['id']; ?>" method="POST">

        <div class="form-group">
            <label for="title">Book Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($review['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" value="<?= htmlspecialchars($review['author']); ?>" required>
        </div>

        <div class="form-group">
            <label for="rating">Rating (1 to 5)</label>
            <input type="number" id="rating" name="rating" value="<?= htmlspecialchars($review['rating']); ?>" min="1" max="5" required>
        </div>

        <div class="form-group">
            <label for="review_text">Review</label>
            <textarea id="review_text" name="review_text" rows="6" required><?= htmlspecialchars($review['review_text']); ?></textarea>
        </div>

        <button type="submit">Update Review</button>
        <a href="admin.php" class="btn btn-secondary">Cancel</a>

    </form>

<?php require "includes/footer.php"; ?>
