<?php
session_start();
include('config.php'); // Adjust the path as necessary

// Check if the user is logged in and has a valid UserID in the session
if (isset($_SESSION['UserID'])) {
    $userId = $_SESSION['UserID'];

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT Username, Email, FirstName, LastName, DateOfBirth, ProfilePicture FROM Users WHERE UserID = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userProfile = $result->fetch_assoc();
    } else {
        echo "Profile not found.";
    }
} else {
    echo "You must be logged in to view this page.";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Profile</title>
</head>

<body>
    <?php if (!empty($userProfile['ProfilePicture'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($userProfile['ProfilePicture']); ?>" alt="Profile Picture" style="width: 100px; height: 100px;"><br>
    <?php endif; ?>

    <form method="post" action="actions/edit-user.php" enctype="multipart/form-data">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName"
            value="<?php echo htmlspecialchars($userProfile['FirstName']); ?>"><br>

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName"
            value="<?php echo htmlspecialchars($userProfile['LastName']); ?>"><br>

        <label for="dateOfBirth">Date of Birth:</label>
        <input type="date" id="dateOfBirth" name="dateOfBirth"
            value="<?php echo htmlspecialchars($userProfile['DateOfBirth']); ?>"><br>

        <label for="profilePicture">Profile Picture:</label>
        <input type="file" id="profilePicture" name="profilePicture"><br>

        <!-- Add more fields for the rest of the user profile information -->

        <input type="submit" value="Save Changes">
    </form>
</body>

</html>