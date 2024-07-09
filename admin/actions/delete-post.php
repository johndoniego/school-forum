<?php
include ('../config.php'); 
session_start();
$postId = $_POST['id'] ?? null; 

if ($postId) {
    try {
        $deleteRepliesQuery = "DELETE replies FROM replies JOIN comments ON replies.CommentID = comments.CommentID WHERE comments.PostID = :PostID";
        $stmt = $conn->prepare($deleteRepliesQuery);
        $stmt->bindParam(':PostID', $postId, PDO::PARAM_INT);
        $stmt->execute();

        // Then, delete related comments
        $deleteCommentsQuery = "DELETE FROM comments WHERE PostID = :PostID";
        $stmt = $conn->prepare($deleteCommentsQuery);
        $stmt->bindParam(':PostID', $postId, PDO::PARAM_INT);
        $stmt->execute();

        // Delete related bookmarks
        $deleteBookmarksQuery = "DELETE FROM bookmarks WHERE PostID = :PostID";
        $stmt = $conn->prepare($deleteBookmarksQuery);
        $stmt->bindParam(':PostID', $postId, PDO::PARAM_INT);
        $stmt->execute();

        // Finally, delete the post
        $deletePostQuery = "DELETE FROM Posts WHERE PostID = :PostID";
        $stmt = $conn->prepare($deletePostQuery);
        $stmt->bindParam(':PostID', $postId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Post, related comments, replies, and bookmarks deleted successfully.";
        } else {
            echo "Error deleting post.";
        }
    } catch (PDOException $e) {
        // Handle any potential exceptions here
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Post ID is required.";
}
?>