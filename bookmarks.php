<?php
session_start(); // Start the session
include('config.php'); // Include your database connection setup

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    die('You must be logged in to view your bookmarks.');
}

$userId = $_SESSION['UserID'];

// Fetch bookmarked posts
$query = "SELECT p.* FROM Posts p INNER JOIN Bookmarks b ON p.PostID = b.PostID WHERE b.UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>My Bookmarks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .bookmark {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .bookmark h3 {
            margin: 0 0 10px 0;
        }

        .bookmark a {
            color: #007bff;
            text-decoration: none;
        }

        .bookmark a:hover {
            text-decoration: underline;
        }

        .user-img {
            width: 40px;
            /* Smaller size */
            height: 40px;
            /* Equal width and height make it a circle */
            border-radius: 50%;
            /* Circular shape */
            object-fit: cover;
            /* Ensures the image covers the area without distorting aspect ratio */
        }

        .card {
            position: relative;
            max-width: 600px;
            /* Smaller width */
            margin: 0 auto;
            /* Centering */
        }

        h3 {
            text-align: center;
        }

        /* Centering h3 */
        .remove-bookmark-btn img {
            width: 20px;
            /* Adjust the width as needed */
            height: auto;
            /* Maintain aspect ratio */
        }
    </style>
</head>

<body>
    <?php include('commons/header.php') ?>
    <?php include('commons/sidebar.php') ?>
    <h3>My Bookmarks</h3>
    <?php if ($result->num_rows > 0) : ?>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Remove Bookmark Button -->
                    <a href="actions/remove-bookmark.php?id=<?= $row['PostID'] ?>" class="remove-bookmark-btn" style="position: absolute; top: 10px; right: 10px;">
                        <img src="assets/img/remove-bookmark.png" alt="Remove Bookmark" style="cursor:pointer;">
                    </a>

                    <h5 class="card-title">
                        <img src="assets/img/placeholder.png" alt="User Image" class="user-img">
                        <a class="post-title" href="post-details.php?id=<?= htmlspecialchars($row['PostID']) ?>">
                            <?php
                            $title = htmlspecialchars($row['Title']);
                            echo mb_substr($title, 0, 69);
                            if (mb_strlen($title) > 50) {
                                echo "...";
                            }
                            ?>
                        </a>
                    </h5>
                    <p class="card-text">
                        <small class="text-muted">Posted on <?= htmlspecialchars($row['CreationDate'] ?? 'Unknown Date') ?></small>
                    </p>
                </div>
            </div>
        <?php endwhile; ?>s
    <?php endif; ?>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>