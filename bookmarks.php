<?php
session_start(); // Start the session

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file for database connection
require_once "config.php";

// Attempt to query database table and retrieve bookmarks
$userId = $_SESSION['user_id']; // Assuming 'user_id' is stored in session upon login
$sql = "SELECT * FROM bookmarks WHERE user_id = ?";

if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_user_id);
    
    // Set parameters
    $param_user_id = $userId;
    
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if any bookmarks exist for the user
        if(mysqli_num_rows($result) > 0){
            // Fetch result rows as an associative array
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                echo "<div><p>" . $row["bookmark_name"] . "</p></div>"; // Display each bookmark
            }
        } else{
            echo "<p>No bookmarks found.</p>"; // No bookmarks found
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}

// Close statement
mysqli_stmt_close($stmt);

// Close connection
mysqli_close($link);
?>