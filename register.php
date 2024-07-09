<?php
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a form field for username, password, and email
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // This is the plaintext password submitted by the user

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Use prepared statements to insert the new user
    $sql = $conn->prepare("INSERT INTO Users (Username, Email, Password) VALUES (?, ?, ?)");
    $sql->bind_param("sss", $username, $email, $hashed_password);
    if ($sql->execute()) {
        header("Location: login.php");
    } else {
        echo "Error: " . $sql->error;
    }
    $sql->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>Registration Page</title>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- CSU Logo -->
                    <div class="text-center mb-4">
                        <img src="images/csulogo.png" alt="CSU Logo" style="width: 100px;"> <!-- Adjust the path and size as needed -->
                    </div>
                    <!-- Registration Form -->
                    <h1 class="text-center mb-4">Register</h1>
                    <form method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                        <div class="text-center mt-3">
                            <a href="login.php">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>