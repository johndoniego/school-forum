<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Center table text */
        .center-table-text td, .center-table-text th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Users List</h2>
        <table class="table table-bordered center-table-text">
            <thead style="background-color: blue;" class="thead-light" >
                <tr>
                    <th>Image</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include('config.php'); // Assuming config.php returns a PDO object in $conn

                try {
                    $sql = "SELECT ProfilePicture, Username, Email, Admin FROM Users";
                    $stmt = $conn->query($sql);
                    
                    if ($stmt->rowCount() > 0) {
                        // Output data of each row
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            // Display user profile picture. Ensure the path is correct.

                            echo "<td><img src='../uploads/user/" . htmlspecialchars($row['ProfilePicture']) . "' alt='User Image' style='width: 50px; height: 50px;'></td>";
                            echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                            echo "<td>" . ($row['Admin'] ? 'Yes' : 'No') . "</td>";
                            // Add an action column with a Ban button
                            echo "<td><button type='button' class='btn btn-danger'>Ban</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>0 results</td></tr>";
                    }
                } catch (PDOException $e) {
                    die("Connection failed: " . $e->getMessage());
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>