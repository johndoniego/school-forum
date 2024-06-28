<?php
session_start();
include('config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['UserID']; // Make sure this user ID exists in your `users` table
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['mytextarea']);
    $creationDate = date('Y-m-d H:i:s'); // Current date and time
    $categoryID = $_POST['category'];

    // Handling multiple file uploads
    $target_dir = "uploads/";
    $uploadOk = 1;
    $uploadedFiles = [];

    // Check if a single file or multiple files are uploaded
    if (!is_array($_FILES["image"]["name"])) {
        $_FILES["image"]["name"] = array($_FILES["image"]["name"]);
        $_FILES["image"]["tmp_name"] = array($_FILES["image"]["tmp_name"]);
    }

    foreach ($_FILES["image"]["name"] as $key => $name) {
        if ($name) { // Proceed only if file name is not empty
            $target_file = $target_dir . basename($name);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is an actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["image"]["tmp_name"][$key]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"][$key], $target_file)) {
                    echo "The file " . htmlspecialchars(basename($name)) . " has been uploaded.";
                    $uploadedFiles[] = $target_file; // Add the uploaded file path to the array
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }

    // Concatenate all uploaded file paths to store in the database
    $imagePaths = implode(',', $uploadedFiles);

    // SQL query to insert post into the databases
    $query = "INSERT INTO Posts (UserID, Title, Content, CreationDate, ImagePath, CategoryID) VALUES ('$user_id', '$title', '$content', '$creationDate', '$imagePaths', '$categoryID')";

    if (mysqli_query($conn, $query)) {
        header("Location: forum.php");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>