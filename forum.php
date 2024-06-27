<?php
include 'config.php'; // Make sure this file contains your database connection
session_start();

// Determine the current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // Number of posts per page
$offset = ($page - 1) * $perPage;

// Fetch the 5 most recent posts with pagination
$sql = "SELECT PostID, Title, Content, ImagePath, CreationDate FROM Posts ORDER BY CreationDate DESC LIMIT $perPage OFFSET $offset";
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
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/main/forum.css">
    <script src="assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.js"></script>
    <script src="./assets/jquery-3.7.1.min.js"></script>
    <script src="tinymce_7.2.0\tinymce\js\tinymce\tinymce.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script type="text/javascript">
    tinymce.init({
        selector: '#mytextarea'
    });
    </script>

    <style>
        .post-title{
            text-decoration: none;
            color: black;
        }
    </style>
</head>

<body>
    <?php include('commons/sidebar.php')?>
    <?php include('commons/header.php')?>
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

    <div class="post-container">
        <div class="container mt-3">
            <div class="btn-group" role="group" aria-label="Post Categories">
                <a href="/all-posts" class="btn btn-primary">All Posts</a>
                <a href="/homework-help" class="btn btn-primary">Homework Help</a>
                <a href="/announcements" class="btn btn-primary">Announcements</a>
                <a href="/events" class="btn btn-primary">Events</a>
                <a href="/general-discussions" class="btn btn-primary">General Discussions</a>
            </div>
        </div>
        <div class="container mt-5">
            <h2>All Posts</h2>
            <?php foreach ($recentPosts as $post): ?>
            <div class="card mb-3">
                <div class="card-body posts">

                    <!-- Post Title -->
                    <h5 class="card-title"><img src="assets/img/placeholder.png" alt="User Image" class="user-img"><a
                            class="post-title" href="post-details.php?id=<?= $post['PostID'] ?? "null" ?>"><?= $post['Title'] ?? 'No Title' ?></a></h5>
                    <p class="card-text"><small class="text-muted">Posted on
                            <?= $post['CreationDate'] ?? 'Unknown Date' ?></small></p>
                </div>
            </div>
            <?php endforeach; ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link"
                            href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
        <!-- recent post -->
        <div class="recent-post-container">
            <div class="container mt-3">
            </div>
            <div class="container mt-5">
                <h2>Recent Posts</h2>
                <?php 
            $limitedPosts = array_slice($recentPosts, 0, 5); // Limit to first 5 posts
            foreach ($limitedPosts as $post): ?>
                <div class="card mb-3">
                    <div class="card-body posts">
                        <h5 class="card-title">
                            <img src="assets/img/placeholder.png" alt="User Image" class="user-img">
                            <a class="post-title" href=""><?php
                              $title = $post['Title'] ?? 'No Title';
                                 echo mb_substr($title, 0, 14);
                                 if (mb_strlen($title) > 14) {
                                  echo "...";
                                    }
                            ?></a>
                        </h5>
                        <p class="card-text"><small class="text-muted">Posted on
                                <?= $post['CreationDate'] ?? 'Unknown Date' ?></small></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
3

</html>