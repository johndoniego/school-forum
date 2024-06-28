<?php
// Assuming you have a session started and a database connection setup in config.php
include('../config.php');
session_start();

// Check if the user is logged in and a post ID is provided
if (!isset($_SESSION['UserID']) || !isset($_GET['id'])) {
    die('You must be logged in to bookmark a post.');
}

$userId = $_SESSION['UserID'];
$postId = $_GET['id'];

// Check if the bookmark already exists
$checkQuery = "SELECT * FROM Bookmarks WHERE UserID = ? AND PostID = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("ii", $userId, $postId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $_SESSION['bookmark_added'] = true; // Set a session flag
    header("Location: ../post-details.php?id=$postId&bookmarked=1");
    exit();
}

// Insert the new bookmark
$insertQuery = "INSERT INTO Bookmarks (UserID, PostID) VALUES (?, ?)";
$insertStmt = $conn->prepare($insertQuery);
$insertStmt->bind_param("ii", $userId, $postId);

if ($insertStmt->execute()) {
    $_SESSION['bookmark_added'] = true; // Set a session flag
    header("Location: ../post-details.php?id=$postId&bookmarked=0");
    exit(); // Ensure no further code is executed after redirect
} else {
    echo "Error bookmarking post.";
}
?>