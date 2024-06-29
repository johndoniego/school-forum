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

// check if bookmarked
$checkQuery = "SELECT * FROM Bookmarks WHERE UserID = ? AND PostID = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("ii", $post["UserID"], $postId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
$is_bookmarked = false;

if ($checkResult->num_rows > 0) {
    $is_bookmarked = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ($post['title']) ?></title>
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

    .actions {
        width: 30px;
        height: 35px;
    }

    .post-container {
        position: relative;
        /* This makes it the reference for absolute positioning of children */
        border: 1px solid black;
    }

    .actions-container {
        position: absolute;
        /* Position the actions container absolutely within the post-container */
        top: 10px;
        /* Distance from the top of the post-container */
        right: 10px;
        /* Distance from the right of the post-container */
    }

    .actions {
        cursor: pointer;
        /* Optional: Changes the cursor to a pointer to indicate it's clickable */
    }
    .reply-textarea {
    width: 100%;
    height: 100px;
    margin-top: 10px;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.reply-button {
    padding: 10px 20px;
    margin-top: 10px;
    border-radius: 5px;
    border: none;
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
}
    </style>
</head>

<body>
    <?php include('commons/sidebar.php') ?>
    <?php include('commons/header.php') ?>

    <div class="container mt-5 post-container">
        <div class="actions-container">
            <?php if ($is_bookmarked) : ?>
            <a href="actions/remove-bookmark.php?id=<?= $post['PostID'] ?>"><img class="actions"
                    src="assets/img/remove-bookmark.png" alt="Remove Bookmark"></a>
            <?php else : ?>
            <a href="actions/bookmark.php?id=<?= $post['PostID'] ?>"><img class="actions"
                    src="assets/img/add-bookmark.png" alt="Add Bookmark"></a>
            <?php endif; ?>
        </div>
        <h1><?= $post['Title'] ?></h1>
        <p><?= nl2br($post['Content']) ?></p>
        <?php if (!empty($post['ImagePath'])) : ?>
        <a href="#imageModal" id="imageLink">
            <img style="border-radius: 15px; " class="post-image" src="<?= $post['ImagePath'] ?>" alt="Post Image">
        </a>
        <?php endif; ?>
        <?php if (isset($_SESSION['UserID'])): ?>
        <br>
        <hr style="width: 100%; margin-top:10px ;">
        <br>
        <br>
        <div class="comment-section">
            <h3>Leave a Comment</h3>
            <form action="actions/submit-comment.php" method="POST">
                <input type="hidden" name="postID" value="<?php echo $postId; ?>">
                <input type="hidden" name="userID" value="<?php echo $_SESSION['UserID']; ?>">
                <div class="form-group">
                    <label for="commentContent">Your Comment</label>
                    <textarea style="height: 10vw;" id="commentContent" name="content" class="form-control"
                        required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Comment</button>
            </form>
        </div>
        <?php endif; ?>

    </div>

    <div class="container mt-5 post-container">
        <h2>Comments</h2>
        <div class="container">
            <?php
            $commentsQueryResult = $conn->query("SELECT * FROM comments WHERE PostID = $postId");
            $comments = [];
            $tree = [];

            while ($comment = $commentsQueryResult->fetch_assoc()) {
                $comments[$comment["CommentID"]] = $comment;
            }

            foreach ($comments as $comment) {
                if ($comment["ParentID"] == 0) {
                    $tree[] = &$comments[$comment["CommentID"]];
                } else {
                    $comments[$comment["ParentID"]]["children"][] = &$comments[$comment["CommentID"]];
                }
            }

            function printComments($comments) {
                foreach ($comments as $comment) {
                    echo "<div style='border: 1px solid black; margin: 10px; padding: 10px; border-radius: 12px;'>";
                    echo $comment["Content"];
                    echo "<br>";
                    echo "ID: ".$comment["CommentID"];
                    echo "<br>";
                    echo "ParentID: ".$comment["ParentID"];
                    //reply button
                    echo '<br><a href="javascript:void(0);" onclick="showReplyBox(' . $comment['CommentID'] . ')" class="reply-link">Reply</a>';
                    echo "</div>";
                    echo "<div style='margin-left: 10px;'>";
                    if (isset($comment["children"])) {
                        printComments($comment["children"]);
                    }
                    echo "</div>";
                }
            }

            printComments($tree);
            ?>
        </div>
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
                    <h5 class="modal-title">Bookmark Status</h5>
                    <button style="color: black;" type="button" class="close" data-dismiss="modal" aria-label="Close">
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

    function showReplyBox(commentId) {
    var replyBoxId = "replyBox" + commentId;
    var existingBox = document.getElementById(replyBoxId);
    if (!existingBox) {
        var replyBox = document.createElement("textarea");
        replyBox.id = replyBoxId;
        replyBox.name = "replyContent";
        replyBox.placeholder = "Write your reply here...";

        replyBox.style.width = "100%";
        replyBox.style.height = "100px";
        replyBox.style.marginTop = "10px";
        replyBox.style.padding = "10px";
        replyBox.style.borderRadius = "5px";
        replyBox.style.border = "1px solid #ccc";
        replyBox.style.boxShadow = "0 2px 4px rgba(0,0,0,0.1)";

        var submitButton = document.createElement("button");
        submitButton.innerText = "Reply";

        submitButton.style.padding = "10px 20px";
        submitButton.style.marginTop = "10px";
        submitButton.style.borderRadius = "5px";
        submitButton.style.border = "none";
        submitButton.style.backgroundColor = "#4CAF50";
        submitButton.style.color = "white";
        submitButton.style.cursor = "pointer";
        submitButton.onclick = function() {
            submitReply(commentId);
        };

        var commentDiv = document.createElement("div");
        commentDiv.appendChild(replyBox);
        commentDiv.appendChild(submitButton);

        var parentDiv = document.querySelector('a[onclick="showReplyBox(' + commentId + ')"]').parentNode;
        parentDiv.appendChild(commentDiv);
    }
}
    </script>

</body>

</html>