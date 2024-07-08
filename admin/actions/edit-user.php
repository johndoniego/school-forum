<?php
session_start();
include('../config.php'); // Adjust the path as necessary

if (isset($_POST['firstName'], $_POST['lastName'], $_POST['dateOfBirth'])) {
    $userId = $_SESSION['UserID'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $fileUploaded = false; // Flag to check if a new file is uploaded

    // Handle file upload for Profile Picture
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
        $targetDirectory = "../uploads/user/"; // Adjusted to the new directory
        $fileName = basename($_FILES["profilePicture"]["name"]);
        $targetFilePath = $targetDirectory . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // You can add more validation (file type, size) here

        // Move the file to the target directory
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFilePath)) {
            $fileUploaded = true; // File upload success
        } else {
            echo "Error uploading file.";
        }
    }

    if ($fileUploaded == true) {
        // If a new file is uploaded, include ProfilePicture in the update
        $stmt = $conn->prepare("UPDATE Users SET FirstName = ?, LastName = ?, DateOfBirth = ?, ProfilePicture = ? WHERE UserID = ?");
        $stmt->bind_param("ssssi", $_POST['firstName'], $_POST['lastName'], $dateOfBirth, $fileName, $userId);
    } else {
        // If no new file is uploaded, do not include ProfilePicture in the update
        $stmt = $conn->prepare("UPDATE Users SET FirstName = ?, LastName = ?, DateOfBirth = ? WHERE UserID = ?");
        $stmt->bind_param("sssi", $_POST['firstName'], $_POST['lastName'], $dateOfBirth, $userId);
    }

    if ($stmt->execute()) {
        header("Location: ../user.php");
        // Redirect back to the profile page or elsewhere
    } else {
        echo "Error updating profile.";
    }
} else {
    echo "Invalid request.";
}