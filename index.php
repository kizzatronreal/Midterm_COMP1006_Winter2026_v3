<?php require "includes/header.php"; ?>

    <h1>Submit a Book Review</h1>

    <form action="process.php" method="POST">

        <div class="form-group">
            <label for="title">Book Title</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" required>
        </div>

        <div class="form-group">
            <label for="rating">Rating (1 to 5)</label>
            <input type="number" id="rating" name="rating" min="1" max="5" required>
        </div>

        <div class="form-group">
            <label for="review_text">Review</label>
            <textarea id="review_text" name="review_text" rows="6" required></textarea>
        </div>

        <button type="submit">Submit Review</button>

    </form>

<?php require "includes/footer.php"; ?>
