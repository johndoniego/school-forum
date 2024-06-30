<?php
session_start();
include('config.php'); // Adjust the path as necessary

if (isset($_SESSION['UserID'])) {
    $userId = $_SESSION['UserID'];

    // Using MySQLi
    $query = "SELECT PostID, Title, Content, CreationDate FROM Posts WHERE UserID = ? ORDER BY CreationDate DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId); // Correct variable name to $userId and use bind_param
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);

    $stmt = $conn->prepare("SELECT Username, Email, FirstName, LastName, DateOfBirth, ProfilePicture FROM Users WHERE UserID = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userProfile = $result->fetch_assoc(); // Fetching single row, assuming UserID is unique

    if (!$userProfile) {
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
    <link rel="stylesheet" href="assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.css">
    <script src="assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/main/user.css">
    <title>User Profile</title>
    <style>

    </style>
</head>

<body>
    <?php include('commons/header.php'); ?>
    <?php include('commons/sidebar.php'); ?>
    <div class="container">
        <div class="content">
            <h2>Profile Information</h2>
            <?php if (!empty($userProfile['ProfilePicture'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($userProfile['ProfilePicture']); ?>" alt="Profile Picture"
                class="profile-picture"><br>
            <?php endif; ?>
            <form method="post" action="actions/edit-user.php" enctype="multipart/form-data">
                <div class="form-group"> want
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName"
                        value="<?php echo htmlspecialchars($userProfile['FirstName']); ?>"><br>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName"
                        value="<?php echo htmlspecialchars($userProfile['LastName']); ?>"><br>
                </div>
                <div class="form-group">
                    <label for="dateOfBirth">Date of Birth:</label>
                    <input type="date" id="dateOfBirth" name="dateOfBirth"
                        value="<?php echo htmlspecialchars($userProfile['DateOfBirth']); ?>"><br>
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
    <div class="user-posts">
        <h3>Your Posts</h3>
        <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
        <div class="post">
            <h4><a style="color: black; text-decoration: none;" class="post-title" href="post-details.php?id=<?= $post['PostID'] ?? "null" ?>"><?php
                              $title = $post['Title'] ?? 'No Title';
                                 echo mb_substr($title, 0, 30);
                                 if (mb_strlen($title) > 30) {
                                  echo "...";
                                    }
                            ?></a></h4>
            <p><?php echo $post['Content']; ?></p>
            <small>Posted on: <?php echo htmlspecialchars($post['CreationDate']); ?></small>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p>You have not posted anything yet.</p>
        <?php endif; ?>
    </div>
    <footer id="main-footer">
    </footer>
</body>

</html>