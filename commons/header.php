
<link rel="stylesheet" href="css/header.css">
<header class="sticky-header">
    <div id="topnav">
        <div class="inner">
            <nav role='navigation'>
                <!-- Search Bar Added Below -->
                <div class="search-bar">
                    <form action="/search" method="get">
                        <input type="text" name="query" placeholder="Search...">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <ul>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPostModal">Create
                        Post</button>
                    <li><a href="#" onclick="checkLogin()"><img style="border-radius: 50% ;" class="user-icon" name="user-icon" src="assets/img/placeholder.png" alt="user"></a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<script>
// Assuming you have a PHP variable that tracks login status, e.g., $_SESSION['logged_in']
var isLoggedIn = <?php echo isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ? 'true' : 'false'; ?>;

function checkLogin() {
    // Use Fetch API to make a request to the PHP script
    fetch('admin/islogin.php') // Adjust the path as necessary
        .then(response => response.json())
        .then(data => {
            if (!data.loggedIn) {
                // If not logged in, show alert and redirect on confirmation
                var confirmLogin = confirm("You must be logged in first.");
                if (confirmLogin) {
                    window.location.href = "login.php";
                }
            } else {
                // If logged in, you can redirect to the user profile or another page
                window.location.href = "./user.php";
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
</script>