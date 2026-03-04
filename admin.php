<?php
require "includes/header.php";
require "includes/connect.php";

// et all reviews from database
$sql = "SELECT * FROM reviews ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$reviews = $stmt->fetchAll();
?>

    <h1>Admin Panel - All Reviews</h1>

    <?php if (count($reviews) === 0): ?>
        <p>No reviews yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?= htmlspecialchars($review['title']); ?></td>
                        <td><?= htmlspecialchars($review['author']); ?></td>
                        <td><?= htmlspecialchars($review['rating']); ?>/5</td>
                        <td><?= htmlspecialchars(substr($review['review_text'], 0, 50)); ?>...</td>
                        <td><?= htmlspecialchars($review['created_at']); ?></td>
                        <td>
                            <a href="edit.php?id=<?= $review['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete.php?id=<?= $review['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this review?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p>
        <a href="index.php" class="btn">Submit New Review</a>
    </p>

<?php require "includes/footer.php"; ?>
