<?php
session_start(); // Start the session
include('../config.php'); // Include your database connection setup

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    die('You must be logged in to perform this action.');
}

// Check if PostID is provided
if (!isset($_GET['id'])) {
    die('Post ID is required.');
}

$userId = $_SESSION['UserID'];
$postId = $_GET['id'];

// Prepare the delete statement
$query = "DELETE FROM Bookmarks WHERE UserID = ? AND PostID = ?";
$stmt = $conn->prepare($query);

// Bind parameters and execute
$stmt->bind_param("ii", $userId, $postId);
$success = $stmt->execute();

if ($success) {
    echo "Bookmark removed successfully.";
    // Redirect back to the bookmarks page or wherever appropriate
    header('Location: ../bookmarks.php');
} else {
    echo "Error removing bookmark.";
}

$stmt->close();
$conn->close();
?>