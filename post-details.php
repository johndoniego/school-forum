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
    $title = $post['Title'] ?? 'Default Title';
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
    <title><?= ($post['Title']) ?></title>
    <link rel="stylesheet" href="assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="tinymce_7.2.0\tinymce\js\tinymce\tinymce.min.js"></script>
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
    <script>
tinymce.init({
  selector: '#postContent',
  inline: true,
});
</script>
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            <a href="actions/remove-bookmark.php?id=<?= $post['PostID'] ?>"><img class="actions" src="assets/img/remove-bookmark.png" alt="Remove Bookmark"></a>
        <?php else : ?>
            <a href="actions/bookmark.php?id=<?= $post['PostID'] ?>"><img class="actions" src="assets/img/add-bookmark.png" alt="Add Bookmark"></a>
        <?php endif; ?>
    </div>
    <h1><?= $post['Title'] ?></h1>
    <p><?= $post['Content']; ?></p>
    <?php if (!empty($post['ImagePath'])) : ?>
        <a href="#imageModal" id="imageLink">
            <img style="border-radius: 15px;" class="post-image" src="<?= $post['ImagePath'] ?>" alt="Post Image">
        </a>
    <?php endif; ?>
    <!-- Edit button for the post creator -->
    <?php if ($_SESSION['UserID'] == $post['UserID']): ?>
        <a href="#" data-toggle="modal" data-target="#editPostModal" style="position: absolute; bottom: 10px; right: 10px;">
            <img src="assets/img/pencil.png" alt="Edit Post" style="width: 30px; height: 30px;">
        </a>
    <?php endif; ?>
        <?php if (isset($_SESSION['UserID'])) : ?>
            <br>
            <hr style="width: 100%; margin-top:10px ;">
            <br>
            <br>
            <div class="comment-section">
                <h3>Leave a Comment</h3>
                <form action="actions/submit-comment.php?order=<?= $_GET["order"] ?? "" ?>" method="POST">
                    <input type="hidden" name="postID" value="<?php echo $postId; ?>">
                    <input type="hidden" name="userID" value="<?php echo $_SESSION['UserID']; ?>">
                    <div class="form-group">
                        <label for="commentContent">Your Comment</label>
                        <textarea style="height: 10vw;" id="commentContent" name="content" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </form>
            </div>
        <?php endif; ?>

    </div>

    <div id="comments" class="container mt-5 post-container">
        <h2>Comments</h2>
        <!-- Sort button with dynamic label and functionality based on current sort order -->
        <?php
        // Assuming $postId is already defined and holds the current post's ID
        $currentOrder = $_GET['order'] ?? 'asc';
        $newOrder = $currentOrder === 'asc' ? 'desc' : 'asc';
        $sortButtonText = $currentOrder === 'asc' ? 'Sort Descending' : 'Sort Ascending';
        ?>
        <button style="margin-bottom: 10px;" class="btn btn-primary sorting" onclick="window.location.href='post-details.php?id=<?php echo $postId; ?>&order=<?php echo $newOrder; ?>#comments'"><?php echo $sortButtonText; ?></button>
        <div class="container">
            <?php
        $sort = $_GET['order'] ?? 'asc';
$commentsQueryResult = $conn->query("SELECT comments.*, users.Username, users.ProfilePicture FROM comments INNER JOIN users ON comments.UserID = users.UserID WHERE PostID = $postId");
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
?>

<?php function printComments($comments)
{
    $currentOrder = $_GET['order'] ?? 'asc';
    foreach ($comments as $comment) {
?>
        <div style='padding: 10px 30px; border-top: 1px solid black; margin-top: 10px;'>
            <!-- Profile Picture -->
            <h6 style="margin-bottom: 10px;">
            <img src="uploads/user/<?= $comment["ProfilePicture"] ?>" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                <?= $comment["Username"] ?></h6>
            <?= $comment["Content"] ?>
            <br>
            <br>
            <!-- Reply button -->
            <a href="javascript:void(0);" onclick="showReplyBox(<?= $comment['CommentID'] ?>, <?= $comment['PostID'] ?>,'<?= $currentOrder ?>')" class="reply-link">
                Reply
            </a>
        </div>
        <div style='margin-left: 50px;'>
            <?php if (isset($comment["children"])) {
                printComments($comment["children"]);
            } ?>
        </div>
<?php }
} ?>

<?php
if ($sort == 'asc') {
    printComments($tree);
} else {
    printComments(array_reverse($tree));
}
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

    <!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="actions/edit-post.php" method="POST"  enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="postID" value="<?= $post['PostID'] ?>">
          <div class="form-group">
            <label for="postTitle">Title</label>
            <input type="text" class="form-control" id="postTitle" name="title" value="<?= $post['Title'] ?>" required>
          </div>
          <div class="form-group">
            <label for="postContent">Content</label>
            <textarea class="form-control" id="postContent" name="content" required><?= $post['Content'] ?></textarea>
            <label for="userImage">Upload Image</label>
            <input type="file" class="form-control-file" id="userImage" name="image">
        </div>
          <!-- Add more fields as needed -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
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

        function showReplyBox(commentId, postID, order) {
            var replyBoxId = "replyBox" + commentId;
            var existingBox = document.getElementById(replyBoxId);
            if (existingBox) {
                let replyContainer = existingBox.parentNode
                existingBox.parentNode.parentElement.removeChild(replyContainer);
            } else {
                var replyContainer = document.createElement("form");
                replyContainer.method = "POST"
                replyContainer.action = "actions/submit-comment.php?order=" + order;
                replyContainer.classList.add("reply-container");

                var postIDInput = document.createElement("input");
                postIDInput.name = "postID"
                postIDInput.value = postID
                postIDInput.type = "hidden"

                var parentID = document.createElement("input");
                parentID.name = "parentID"
                parentID.value = commentId
                parentID.type = "hidden"

                var replyBox = document.createElement("textarea");
                replyBox.name = "content"
                replyBox.id = replyBoxId;
                replyBox.placeholder = "Write your reply here...";

                replyBox.style.cssText = `
                width: 100%;
                height: 100px;
                margin-top: 10px;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            `;

                var submitButton = document.createElement("button");
                submitButton.type = "submit";
                submitButton.innerText = "Reply";

                submitButton.style.cssText = `
                padding: 10px 20px;
                margin-top: 10px;
                border-radius: 5px;
                border: none;
                background-color: #4CAF50;
                color: white;
                cursor: pointer;
            `;
                replyContainer.appendChild(replyBox);
                replyContainer.appendChild(submitButton);
                replyContainer.appendChild(parentID);
                replyContainer.appendChild(postIDInput);

                console.log('a[onclick^="showReplyBox(' + commentId + '"]')
                var parentDiv = document.querySelector('a[onclick^="showReplyBox(' + commentId + '"]').parentNode;
                parentDiv.appendChild(replyContainer);
            }
        }
    </script>
    <script>
$('#editPostModal').on('shown.bs.modal', function () {
  if (!tinymce.get('postContent')) { // Initialize TinyMCE if not already initialized
    tinymce.init({
      selector: '#postContent',
      // Additional options...
    });
  }
});
$('#editPostModal').on('hidden.bs.modal', function () {
  if (tinymce.get('postContent')) { // Destroy TinyMCE instance after modal is closed
    tinymce.get('postContent').remove();
  }
});
</script>
</body>

</html>