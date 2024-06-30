<?php
session_start();
include('../config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['postID'])) {
    $postID = $_POST['postID'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Initialize an array to hold paths of successfully uploaded files
    $uploadedFiles = [];

    // Check if files were uploaded
    if (!empty($_FILES['image']['name'][0])) {
        $target_dir = "uploads/";
        foreach ($_FILES["image"]["name"] as $key => $name) {
            $target_file = $target_dir . basename($name);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validate file is an image
            $check = getimagesize($_FILES["image"]["tmp_name"][$key]);
            if ($check !== false) {
                // Attempt to move the uploaded file to its new location
                if (move_uploaded_file($_FILES["image"]["tmp_name"][$key], $target_file)) {
                    $uploadedFiles[] = $target_file; // Add the uploaded file path to the array
                } else {
                    echo "Sorry, there was an error uploading your file: " . htmlspecialchars($name) . ".";
                }
            } else {
                echo "File is not an image: " . htmlspecialchars($name) . ".";
            }
        }
    }

    // Concatenate all uploaded file paths to store in the database
    $imagePaths = implode(',', $uploadedFiles);

    // Prepare SQL query to update the post
    $query = "UPDATE Posts SET Title = ?, Content = ?, ImagePath = ? WHERE PostID = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $title, $content, $imagePaths, $postID);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../post-details.php?id=" . $postID); // Redirect to the post details page
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>