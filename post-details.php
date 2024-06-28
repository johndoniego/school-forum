<?php
// Assuming you have a database connection setup in db.php
include('config.php');
session_start();
// Check if the bookmark was added
if (isset($_SESSION['bookmark_added']) && $_SESSION['bookmark_added']) {
    // Prepare JavaScript to show the modal
    echo "<script>document.addEventListener('DOMContentLoaded', function() { $('#bookmarkSuccessModal').modal('show'); });</script>";
    unset($_SESSION['bookmark_added']); // Clear the flag
}

// Get the post ID from the URL
$postId = $_GET['id'] ?? null;

// Fetch the post from the database
$query = "SELECT * FROM posts WHERE PostID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $postId);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    die('Post not found.');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
    <style>
        .post-container {
            max-width: 60%;
            /* Adjust the width as needed */
            margin: 0 auto;
            /* Centers the container horizontally */
            padding: 20px;
            /* Adds padding inside the container */
            word-wrap: break-word;
            /* Correct property name for breaking long words */
            overflow-wrap: break-word;
            /* Ensures overflow text is wrapped */
            border-radius: 10px;
        }

        .post-image {
            width: auto;
            /* Allows the image to scale based on its height */
            height: 300px;
            /* Default height */
            max-width: 100%;
            /* Ensures the image is not wider than its container */
            object-fit: cover;
            /* Adjusts the image's fit within the box without stretching it */
        }

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1001;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.9);
            /* Black w/ opacity */
        }

        .bookmarked {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1002;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.9);
            /* Black w/ opacity */
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-backdrop.show {
            display: none;
        }
    </style>
</head>

<body>
    <?php include('commons/sidebar.php') ?>
    <?php include('commons/header.php') ?>

    <div class="container mt-5  post-container" style="border:1px solid black;">
        <h1><?= $post['Title'] ?></h1>
        <a href="actions/bookmark.php?id=<?= $post['PostID'] ?>">Bookmark this post</a>
        <p><?= nl2br($post['Content']) ?></p>
        <a href="#imageModal" id="imageLink">
            <img class="post-image" src="<?= $post['ImagePath'] ?? '' ?>" alt="Post Image">
        </a>
        <p></p>
    </div>
    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <!-- Bookmark Success Modal -->
    <div class="modal" id="bookmarkSuccessModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php
                        if ($_GET["bookmarked"] == "0") {
                            echo "Post bookmarked successfully!";
                        } else {
                            echo "Post already bookmarked!";
                        }
                        ?></p>
                </div>
                <div class="modal-footer">
                    <a href="bookmarks.php" class="btn btn-primary">View Bookmarks</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('imageLink').onclick = function(event) {
            event.preventDefault(); // Prevent the default anchor action
            var modal = document.getElementById('imageModal');
            var modalImg = document.getElementById('modalImage');
            modal.style.display = "block";
            modalImg.src = this.children[0].src; // Set the src of the modal image to the clicked image's src
        };

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            var modal = document.getElementById('imageModal');
            modal.style.display = "none";
        }
    </script>

</body>

</html>