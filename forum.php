<?php
include 'config.php'; // Make sure this file contains your database connection
session_start();

// Determine the current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5; // Number of posts per page
$offset = ($page - 1) * $perPage;

// Fetch the 5 most recent posts with pagination
$sql = "SELECT Title, Content, ImagePath, CreationDate FROM Posts ORDER BY CreationDate DESC LIMIT $perPage OFFSET $offset";
$result = $conn->query($sql);
$recentPosts = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $recentPosts[] = $row;
    }
}

// Fetch total number of posts
$totalSql = "SELECT COUNT(*) as total FROM Posts";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalPosts = $totalRow['total'];
$totalPages = ceil($totalPosts / $perPage);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the CSU Forum</title>
    <link rel="stylesheet" href="assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="css/sidebar.css">    
    <script src="assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.js"></script>
    <script src="./assets/jquery-3.7.1.min.js"></script>
    <script src="tinymce_7.2.0\tinymce\js\tinymce\tinymce.min.js"></script>
    <script type="text/javascript">
    tinymce.init({
        selector: '#mytextarea'
    });
    </script>
</head>

<body>
  <?php include('commons/sidebar.php')?>

    <!-- Create Post Modal -->
    <div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPostModalLabel">Create New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="create_post.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="postTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="postTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="mytextarea" class="form-label">Content</label>
                            <textarea id="mytextarea" name="mytextarea" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="postImage" class="form-label">Add Image</label>
                            <input type="file" class="form-control" id="postImage" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2>Recent Posts</h2>
        <?php foreach ($recentPosts as $post): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= $post['Title'] ?? 'No Title' ?></h5>
                <p class="card-text"><?= $post['Content'] ?? 'No Content' ?></p>
                <p class="card-text"><?= $post['ImagePath'] ?? '' ?></p>
                <p class="card-text"><small class="text-muted">Posted on
                        <?= $post['CreationDate'] ?? 'Unknown Date' ?></small></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link"
                    href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>

</body>

</html>