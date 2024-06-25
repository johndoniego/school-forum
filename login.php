<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body style="background-image: url('images/low-poly-grid-haikei.png');"></body>
    <div class="parent">
        <div class="left-div">
            <img src="images/csulogo.png" style="display: block; margin: 0 auto; width: 200px; height: 200px;">
        </div>
        <div class="middle-div">
            <marquee direction="down" height="250%" scrollamount="30"><p>WELCOME CSUAN!</p></marquee>
        </div>    
        <div class="right-div">
            <h1>Login</h1>
            <form method="post">
                <input type="text" id="username" name="username" placeholder="Identification Number" required>
                <br>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <br>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <br>
                <button type="submit">Login</button>
                <br>
                <a href="register.php">Register</a>
            </form>
        </div>
    </div>

    <?php
    include 'config.php';
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password']; // This is the plaintext password submitted by the user

        // Use prepared statements to prevent SQL Injection
        $sql = $conn->prepare("SELECT UserID, Password FROM Users WHERE Username = ? AND Email = ?");
        $sql->bind_param("ss", $username, $email);
        $sql->execute();
        $result = $sql->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['Password'])) {
                // Password is correct, store the user ID in session
                $_SESSION['UserID'] = $user['UserID'];
                header("Location: forum.php");
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
