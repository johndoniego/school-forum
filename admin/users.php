<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the CSU Forum</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.css">
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.js"></script>
    <script src="../assets/jquery-3.7.1.min.js"></script>
    <script src="../tinymce_7.2.0\tinymce\js\tinymce\tinymce.min.js"></script>
    <link rel="stylesheet" href="../css/sidebar.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <style>
    /* Center table text */
    .center-table-text td, .center-table-text th {
        text-align: center;
        vertical-align: middle;
    }
    
    /* Make table width smaller and center it */
    .table {
        width: 79%; /* Adjust this value as needed */
        margin: auto;
    }

    /* Move the table lower by adding top margin to the container */
    .container {
        margin-top: 50px; /* Adjust this value as needed */
    }
    </style>
</head>
<body>
    <?include('commons/header.php')?>
    <?include('commons/sidebar.php')?>
    
    <div class="container mt-5">
        <table class="table table-bordered center-table-text" id="usersTable">
            <thead style="background-color: #0d6efd; color: white;" class="thead-light" >
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
    // Ensure the SQL query selects the Ban column
    $sql = "SELECT UserID, ProfilePicture, Username, Email, Admin, Ban FROM Users";
    $stmt = $conn->query($sql);
    
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) > 0) {
        foreach ($rows as $row) {
            ?>
            <tr>
                <td><img src="../uploads/user/<?= htmlspecialchars($row['ProfilePicture']) ?>" alt="User Image" style="width: 50px; height: 50px;"></td>
                <td><?= htmlspecialchars($row['Username']) ?></td>
                <td><?= htmlspecialchars($row['Email']) ?></td>
                <td><?= $row['Admin'] ? 'Yes' : 'No' ?></td>
                <td>
                    <?php if ($row['Ban'] == 1): ?>
                        <button type="button" class="btn btn-primary unban-btn" data-id="<?= htmlspecialchars($row['UserID']) ?>">Unban</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-danger ban-btn" data-id="<?= htmlspecialchars($row['UserID']) ?>">Ban</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr><td colspan="5">0 results</td></tr>
        <?php
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
</tbody>
</table>
    </div>
    <!-- Bootstrap JS, Popper.js, and full jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
$(document).ready(function() {
    // Handler for ban action
    $('body').on('click', '.ban-btn', function() {
        var userId = $(this).data('id');
        $.ajax({
            url: 'actions/ban-user.php',
            type: 'POST',
            data: {id: userId, action: 'ban'},
            success: function(response) {
                alert(response);
                // Change the button to "Unban" and make it blue
                $('[data-id="' + userId + '"]').removeClass('btn-danger ban-btn').addClass('unban-btn btn-primary').text('Unban');
            },
            error: function() {
                alert('Error changing user status');
            }
        });
    });

    // Handler for unban action
    $('body').on('click', '.unban-btn', function() {
            var userId = $(this).data('id');
            $.ajax({
                url: 'actions/unban-user.php',
                type: 'POST',
                data: {id: userId, action: 'unban'},
                success: function(response) {
                    alert(response);
                    // Change the button back to "Ban"
                    $('[data-id="' + userId + '"]').removeClass('btn-primary unban-btn').addClass('ban-btn btn-danger').text('Ban');
                },
                error: function() {
                    alert('Error changing user status');
                }
            });
        });
    });
</script>
</body>