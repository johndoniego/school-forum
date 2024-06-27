<?php
// Assuming you have a database connection setup in db.php
include('config.php');
session_start();

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
    <!-- <link rel="stylesheet" href="css/sidebar.css"> -->
    <!-- <link rel="stylesheet" href="css/header.css"> -->
</head>
<body>
<?php include('commons/sidebar.php')?>
<?php include('commons/header.php')?>
    <div class="container mt-5">
        <h1><?= ($post['Title']) ?></h1>
        <p><?= nl2br($post['Content']) ?></p>
    </div>
</body>
</html>