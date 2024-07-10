<?php
// Assuming you have a separate file for database connection called 'db.php'
include '../config.php';

// Check if the 'id' POST variable is set
if (isset($_POST["id"])) {
    $id = $_POST["id"];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE Users SET Ban = FALSE WHERE UserID = :id");
    // Bind the parameter
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Use bindParam() for PDO

    // Execute the statement and check if it was successful
    if ($stmt->execute()) {
        echo "unbanned user";
    } else {
        echo "error";
    }

    // No need to explicitly close the statement in PDO
} else {
    echo "No ID provided";
}

// Close the database connection
$conn = null; // Correct way to close a PDO connection