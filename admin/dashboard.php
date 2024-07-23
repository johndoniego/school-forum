<?php
include 'config.php'; // Ensure this file properly handles database connection errors
session_start();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;
$categoryID = $_GET['category'] ?? 0;
$categoryQuery = $categoryID ? "WHERE Posts.CategoryID = $categoryID" : "";

// get category name
$categoryName = $conn->query("SELECT Categories.CategoryName FROM Categories WHERE CategoryID = $categoryID")->fetch(PDO::FETCH_ASSOC);
$categoryName = $categoryName['CategoryName'] ?? 'All Posts';

// Updated SQL query to include CategoryName
$sql = "SELECT Posts.PostID, Posts.Title, Posts.Content, Posts.ImagePath, Posts.CreationDate, Users.ProfilePicture, Users.Username, Categories.CategoryName FROM Posts JOIN Users ON Posts.UserID = Users.UserID JOIN Categories ON Posts.CategoryID = Categories.CategoryID $categoryQuery ORDER BY CreationDate DESC LIMIT :perPage OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recentPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalSql = "SELECT COUNT(*) as total FROM Posts $categoryQuery";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch(PDO::FETCH_ASSOC);
$totalPosts = $totalRow['total'];
$totalPages = ceil($totalPosts / $perPage);

function fetchLimitedRecentPosts($conn, $limit = 5) {
    // Updated SQL query in the function to include CategoryName
    $sql = "SELECT Posts.PostID, Posts.Title, Posts.Content, Posts.ImagePath, Posts.CreationDate, Users.ProfilePicture, Categories.CategoryName FROM Posts JOIN Users ON Posts.UserID = Users.UserID JOIN Categories ON Posts.CategoryID = Categories.CategoryID ORDER BY Posts.CreationDate DESC LIMIT :limit";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the CSU Forum</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.css">
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.js"></script>
    <script src="../assets/jquery-3.7.1.min.js"></script>
    <script src="../tinymce_7.2.0\tinymce\js\tinymce\tinymce.min.js"></script>
    <link rel="stylesheet" href="../css/sidebar.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script type="text/javascript">
    tinymce.init({
        selector: '#mytextarea'
    });
    </script>

    <style>
    .post-title {
        text-decoration: none;
        color: black;
    }

    .center {
        text-align: center;
    }

    .user-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .posts {
        width: 100%;
    }
    .test {
        display: flex;
        justify-content: center;
    }
    .post-container {
        width: 50%;
    }
    .category {
        color: #0d6efd;
        font-size: 1rem;
    }
    </style>
</head>

<body>
    <?php include('commons/header.php')?>
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
                    <form action="../create_post.php" method="POST" enctype="multipart/form-data">
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
                        <!-- Category Selection Dropdown -->
                        <div class="mb-3">
                            <label for="postCategory" class="form-label">Category</label>
                            <select class="form-select" id="postCategory" name="category">
                                <!-- Dynamically populated options -->
                                <option value="1">Homework Help</option>
                                <option value="2">Club Announcements</option>
                                <option value="3">Event Updates</option>
                                <option value="4">General Discussion</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="test">
        <div class="post-container">
            <div class="container mt-3 center">
                <div class="btn-group" role="group" aria-label="Post Categories">
                    <a href="dashboard.php" class="btn btn-primary">All Posts</a>
                    <a href="dashboard.php?category=1" class="btn btn-primary">Homework Help</a>
                    <a href="dashboard.php?category=2" class="btn btn-primary">Announcements</a>
                    <a href="dashboard.php?category=3" class="btn btn-primary">Events</a>
                    <a href="dashboard.php?category=4" class="btn btn-primary">General Discussions</a>
                </div>
            </div>
            <div class="container mt-5 ">
                <h2><?= $categoryName ?></h2>
                <?php foreach ($recentPosts as $post): ?>
                <div class="card mb-3 posts">
                    <div class="card-body">
                        <!-- delete button -->
                        <button type="button" class="btn btn-danger delete-post-btn" onclick="deletePost(<?php echo $post['PostID']; ?>)"
                        style="float: right;">Delete Post</button>
                        <!-- User Name and Post Title -->
                        <div class="post-user">
                            <img src="../uploads/user/<?= htmlspecialchars($post['ProfilePicture'] ?? "") ?>"
                                alt="User Image" class="user-img">
                            <?= $post['Username'] ?? "Unknown"; ?>
                            <?php echo '<h2 class="category">' .  $post['CategoryName'] . "</h2>"; ?>
                        </div>
                        <h5 class="card-title">

                            <a class="post-title"
                                href="../post-details.php?id=<?= htmlspecialchars($post['PostID'] ?? "null") ?>">
                                <?php
                $title = $post['Title'] ?? 'No Title';
                echo htmlspecialchars(mb_substr($title, 0, 40));
                if (mb_strlen($title) > 40) {
                    echo "...";
                }
                ?>
                            </a>
                        </h5>
                        <p class="card-text"><small class="text-muted">Posted on
                                <?= htmlspecialchars($post['CreationDate'] ?? 'Unknown Date') ?></small></p>
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
        </div>
    </div>
    <script>
function deletePost(postId) {
    if (confirm('Are you sure you want to delete this post?')) {
        fetch('actions/delete-post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + postId
        })
        .then(response => response.text())
        .then(data => {
            // Handle response data, e.g., refresh the page or show a message
            console.log(data); // For debugging
            window.location.reload(); // Reload the page to reflect the changes
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
}
</script>
</body>

</html>