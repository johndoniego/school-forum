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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
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
            <h1>Register</h1>
            <form method="post">
                <input type="text" id="username" name="username" placeholder="Identification Number" required>
                <br>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <br>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <br>
                <button type="submit">Register</button>
                <br>
                <a href="login.php">Login</a>
            </form>
        </div>
    </div>
</html>