<?php
session_start();
include('../config.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
echo "Hello";
function submitComment($conn) {
    try {
        if (isset($_SESSION['UserID']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $postID = isset($_POST['postID']) ? $conn->real_escape_string($_POST['postID']) : null;
            $userID = $_SESSION['UserID'];
            $content = isset($_POST['content']) ? $conn->real_escape_string($_POST['content']) : '';
            $parentID = isset($_POST['parentID']) ? $conn->real_escape_string($_POST['parentID']) : 0;
            $order = isset($_GET['order']) ? $_GET['order'] : 'asc';

            if (empty($postID) || empty($content)) {
                echo "Post ID or content is missing.";
                return;
            }

            $stmt = $conn->prepare("INSERT INTO Comments (PostID, UserID, Content, ParentID) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("iisi", $postID, $userID, $content, $parentID);

            if ($stmt->execute()) {
                header("Location: ../post-details.php?id=$postID&order=$order#comments");
                exit();
            } else {
                echo "Error submitting comment.";
            }
        } else {
            echo "You must be logged in to submit a comment.";
        }
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}

submitComment($conn);
?>