<?php
include ('config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to EIS</title>
  <link rel="stylesheet" href="assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.css">
  <script src="assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.js"></script>
  <script src="./assets/jquery-3.7.1.min.js"></script>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
        aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="index.php">Forum</a>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="history.php">History</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link active" href="profile.php">Profile</a>
          </li>
        </ul>
        <form class="d-flex">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>



  <br>

  <main class="container">





    <div class="card">
      <div class="card-header">
        <h3 style="text-align:center;">List of Users</h3>

        <!-- Button trigger modal -->
        

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">User Form</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form method="POST" id="myForm" action="add-user.php" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="text" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                  </div>
                  <div class="mb-3">
                    <label for="name" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Access Level</label>
                    <select class="form-select" id="accesslevel" name="accesslevel">
                      <option value="admin">Admin</option>
                      <option value="user">User</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">User Image</label>
                    <input type="file" class="form-control" id="userimage" name="userimage">
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
              </div>


              </form>

            </div>
          </div>
        </div>

      </div>
      <div class="card-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>UserID</th>
              <th>UserName</th>
              <th>Password</th>
              <th>Access Level</th>
              <th>User Image</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>

            <?php
            $sql = mysqli_query($conn, "SELECT * FROM tbluseruser WHERE 1");
            while ($rows = mysqli_fetch_array($sql)) {
              ?>

              <tr>
                <td> <?php echo $rows[0]; ?> </td>
                <td> <?php echo $rows[1]; ?> </td>
                <td> <?php echo str_repeat('*', strlen($rows[2])); ?> </td>
                <td> <?php echo $rows[3]; ?></td>
                <td>
                  <?php if (!empty($rows[4])) { ?>
                    <img src="./images/<?= $rows[4]; ?>" width="50px" height="50px" style="border-radius:50%; border: 0px;" alt="">
                  <?php } else { ?>
                    <img src="placeholder.png" width="50px" height="50px" style="border-radius:50%; border: 0px;" alt="">
                  <?php } ?>
                </td>
                <td>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" onclick="edituser(<?= $rows[0]; ?>);">
                    Edit
                  </button>
                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delModal" onclick="deletexid(<?= $rows[0]; ?>);">
                    Delete
                  </button>
                </td>
              </tr>
              
              </tr>
              
            </tbody>
          <?php } ?>
          
        </table>
      </div>
      <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form method="POST" id="myForm" action="edit_user.php" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="text" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                  </div>
                  <div class="mb-3">
                    <label for="name" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Access Level</label>
                    <select class="form-select" id="accesslevel" name="accesslevel">
                      <option value="admin">Admin</option>
                      <option value="user">User</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">User Image</label>
                    <input type="file" class="form-control" id="userimage" name="userimage">
                  </div>
              
            </div>
            <div class="modal-footer">
                <input type="hidden" id="editid" name="eid">
                <button type="submit" name="submit" class="btn btn-primary">Confirm</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              
            </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade" id="delModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete?
            </div>
            <div class="modal-footer">
              <form action="del_user.php" method="POST">
                <input type="hidden" id="delid" name="xid">
                <button type="submit" name="submit" class="btn btn-primary">Yes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
              </form>
            </div>
          </div>
        </div>
      </div>              
      <button type="button" class="btn btn-success" style="border-radius:0;" data-bs-toggle="modal" data-bs-target="#exampleModal">
          Add
        </button>
      <div class="card-footer">
        <?php echo mysqli_num_rows($sql) . " record/s found"; ?>
      </div>
    </div>
  </main>


</body>

</html>
<script>
  function deletexid(x) {
    document.getElementById('delid').value = x;
  }
  function edituser(x) {
    document.getElementById('editid').value = x;
  }
</script>