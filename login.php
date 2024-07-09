<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>Login Page</title>
    <style>
    </style>
</head>

<body style="">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- CSU Logo -->
                    <div class="text-center mb-4">
                        <img src="uploads/csulogo.png" alt="CSU Logo" style="width: 100px;"> <!-- Adjust the path and size as needed -->
                    </div>
                    <!-- Login Form -->
                    <form method="post" class="mt-4">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                        <div class="text-center mt-3">
                            <a href="register.php">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
    include 'config.php';
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password']; // This is the plaintext password submitted by the user

        // Use prepared statements to prevent SQL Injection
        $sql = $conn->prepare("SELECT UserID, Password FROM Users WHERE Username = ?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $result = $sql->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['Password'])) {
                // Password is correct, store the user ID in session
                $_SESSION['UserID'] = $user['UserID'];
                $_SESSION['Admin'] = $user['Admin'];
                header("Location: index.php");
                exit(); // Prevent further script execution after redirect
            } else {
                echo "<script>alert('Invalid username or password!')</script>";
            }
        } else {
            echo "<script>alert('Invalid username or password!')</script>";
        }
        $sql->close();
    }
?>
</body>

</html>