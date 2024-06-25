<?php
include 'config.php'; // Make sure this file contains your database connection
session_start();

// Fetch the 5 most recent posts
$sql = "SELECT Title, Content, ImagePath, CreationDate FROM Posts ORDER BY CreationDate DESC LIMIT 5";
$result = $conn->query($sql);
$recentPosts = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $recentPosts[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to the CSU Forum</title>
  <link rel="stylesheet" href="assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.css">
  <script src="assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.js"></script>
  <script src="assets/jquery-3.7.1.js"></script>
  <script src="tinymce_7.2.0\tinymce\js\tinymce\tinymce.min.js"></script>
  <script type="text/javascript">
  tinymce.init({
    selector: '#mytextarea'
  });
  </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="forum.php">Forum</a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="history.php">History</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="profile.php">Profile</a>
        </li>
      </ul>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPostModal">Create Post</button>
    </div>
  </div>
</nav>

<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
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
                <h5 class="card-title"><?= htmlspecialchars($post['Title'] ?? 'No Title') ?></h5>
                <p class="card-text"><?= htmlspecialchars($post['Content'] ?? 'No Content') ?></p>
                <p class="card-text"><?= htmlspecialchars($post['ImagePath'] ?? '') ?></p>
                <p class="card-text"><small class="text-muted">Posted on <?= $post['CreationDate'] ?? 'Unknown Date' ?></small></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>


</body>
</html>