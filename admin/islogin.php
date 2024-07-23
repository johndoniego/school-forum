<?php
include('../config.php'); // Adjust the path as necessary
session_start();

if (isset($_SESSION['UserID'])) {
    $userId = $_SESSION['UserID'];
    $stmt = $conn->prepare("SELECT UserID FROM Users WHERE UserID = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User is logged in
        echo json_encode(['loggedIn' => true]);
    } else {
        // User table is empty or user not found
        echo json_encode(['loggedIn' => false]);
    }
} else {
    // Session UserID not set
    echo json_encode(['loggedIn' => false]);
}
?>