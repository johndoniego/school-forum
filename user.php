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
        exit();
    }
} else {
    echo "You must be logged in to view this page.";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            margin: auto;
            flex: 1;
        }
        #main-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
        #main-footer {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }
        .content {
            background: #fff;
            padding: 20px;
            margin-top: 30px;
            text-align: center;
        }
        .profile-picture {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"], .form-group input[type="date"], .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header id="main-header">
        <h1>User Profile</h1>
    </header>
    <div class="container">
        <div class="content">
            <h2>Profile Information</h2>
            <?php if (!empty($userProfile['ProfilePicture'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($userProfile['ProfilePicture']); ?>" alt="Profile Picture" class="profile-picture"><br>
            <?php endif; ?>
            <form method="post" action="actions/edit-user.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($userProfile['FirstName']); ?>"><br>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($userProfile['LastName']); ?>"><br>
                </div>
                <div class="form-group">
                    <label for="dateOfBirth">Date of Birth:</label>
                    <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($userProfile['DateOfBirth']); ?>"><br>
                </div>
                <div class="form-group">
                    <label for="profilePicture">Profile Picture:</label>
                    <input type="file" id="profilePicture" name="profilePicture"><br>
                </div>
                <div class="form-group">
                    <input type="submit" value="Save Changes">
                </div>
            </form>
        </div>
    </div>
    <footer id="main-footer">
    </footer>
</body>
</html>