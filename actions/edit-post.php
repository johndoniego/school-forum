<?php
session_start();
include('../config.php');

function moveImage()
{
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            return "uploads/" . basename($_FILES["image"]["name"]);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['postID'])) {
    $postID = $_POST['postID'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $targetFile = moveImage();
    echo $targetFile;

    // Prepare SQL query to update the post
    $query = "UPDATE Posts SET Title = ?, Content = ?, ImagePath = ? WHERE PostID = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $title, $content, $targetFile, $postID);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../post-details.php?id=" . $postID); // Redirect to the post details page
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
